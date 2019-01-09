<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Console\Command;

use Contentful\Management\Client;
use Contentful\Management\CodeGenerator\Entry;
use Contentful\Management\CodeGenerator\Loader;
use Contentful\Management\CodeGenerator\Mapper;
use Contentful\Management\Proxy\EnvironmentProxy;
use Contentful\Management\Query;
use Contentful\Management\Resource\ContentType;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * GenerateEntryClassesCommand class.
 *
 * CLI command for generating entry classes based off content types.
 */
class GenerateEntryClassesCommand extends Command
{
    /**
     * @var array
     */
    private $createdFiles = [];

    /**
     * @var string
     */
    private $loaderFile;

    protected function configure()
    {
        $this
            ->setName('generate:entry-classes')
            ->setDescription('Generates entry classes based off available content types')
            ->setDefinition([
                new InputOption('access-token', 't', InputOption::VALUE_REQUIRED, 'OAuth or personal access token'),
                new InputOption('space-id', 's', InputOption::VALUE_REQUIRED, 'ID of the space to use'),
                new InputOption('environment-id', 'e', InputOption::VALUE_REQUIRED, 'ID of the environment to use', 'master'),
                new InputOption('dir', 'd', InputOption::VALUE_REQUIRED, 'The directory to write the files in'),
                new InputOption('namespace', 'ns', InputOption::VALUE_OPTIONAL, 'The base namespace to use'),
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $spaceId = $input->getOption('space-id');
        $environmentId = $input->getOption('environment-id');
        $dir = $input->getOption('dir');

        $this->performDirectoryChecks($dir);

        $this->generate(
            $input->getOption('access-token'),
            $spaceId,
            $environmentId,
            $input->getOption('namespace')
        );

        $this->writeFiles($dir);

        $this->writeReport($output, $spaceId, $environmentId, $dir);
    }

    /**
     * @param string $dir
     *
     * @throws \RuntimeException
     */
    private function performDirectoryChecks(string $dir)
    {
        if (!\is_dir($dir) && !\is_writable(\dirname($dir))) {
            throw new \RuntimeException(\sprintf(
                'Directory "%s" does not exist and can not be created.',
                $dir
            ));
        }
    }

    /**
     * @param string $accessToken
     * @param string $spaceId
     * @param string $environmentId
     * @param string $namespace
     */
    private function generate(string $accessToken, string $spaceId, string $environmentId, string $namespace)
    {
        $client = new Client($accessToken);
        $environment = $client->getEnvironmentProxy($spaceId, $environmentId);

        $defaultLocale = $this->determineDefaultLocale($environment);
        $contentTypes = $this->getAllContentTypes($environment);

        $entryGenerator = new Entry($defaultLocale);
        $mapperGenerator = new Mapper($defaultLocale);

        $this->loaderFile = (new Loader($defaultLocale))
            ->generate(['content_types' => $contentTypes, 'namespace' => $namespace])
        ;

        foreach ($contentTypes as $contentType) {
            $contentTypeId = $contentType->getId();
            $className = $this->convertToStudlyCaps($contentTypeId);

            $this->createdFiles[] = [
                'content_type' => $contentType,
                'entry' => [
                    'path' => $className.'.php',
                    'contents' => $entryGenerator->generate(['content_type' => $contentType, 'namespace' => $namespace]),
                ],
                'mapper' => [
                    'path' => 'Mapper/'.$className.'Mapper.php',
                    'contents' => $mapperGenerator->generate(['content_type' => $contentType, 'namespace' => $namespace]),
                ],
            ];
        }
    }

    /**
     * @param EnvironmentProxy $environment
     *
     * @return ContentType[]
     */
    private function getAllContentTypes(EnvironmentProxy $environment): array
    {
        $skip = 0;
        $limit = 100;

        $allContentTypes = [];
        $query = (new Query())
            ->setLimit($limit)
        ;

        do {
            $query->setSkip($skip);
            $array = $environment->getContentTypes($query);
            /** @var ContentType[] $contentTypes */
            $contentTypes = $array->getItems();
            $allContentTypes += $contentTypes;

            $skip += $limit;
        } while ($array->getTotal() > $skip);

        return $allContentTypes;
    }

    /**
     * @param EnvironmentProxy $environment
     *
     * @return string
     */
    private function determineDefaultLocale(EnvironmentProxy $environment): string
    {
        $defaultLocale = 'en-US';

        $locales = $environment->getLocales();
        foreach ($locales as $locale) {
            if ($locale->isDefault()) {
                $defaultLocale = $locale->getCode();

                break;
            }
        }

        return $defaultLocale;
    }

    /**
     * Converts a string to StudlyCaps.
     *
     * @param string $name
     *
     * @return string
     */
    protected function convertToStudlyCaps(string $name): string
    {
        return \ucwords(\str_replace(['-', '_'], ' ', $name));
    }

    /**
     * @param string $dir
     */
    private function writeFiles(string $dir)
    {
        foreach ($this->createdFiles as $content) {
            $this->writeFile(
                $dir.'/'.$content['entry']['path'],
                $content['entry']['contents']
            );
            $this->writeFile(
                $dir.'/'.$content['mapper']['path'],
                $content['mapper']['contents']
            );
        }

        $this->writeFile(
            $dir.'/_loader.php',
            $this->loaderFile
        );
    }

    /**
     * @param string $path
     * @param string $content
     *
     * @return bool
     */
    private function writeFile(string $path, string $content): bool
    {
        if (!\is_dir(\dirname($path))) {
            \mkdir(\dirname($path), 0777, \true);
        }

        return (bool) \file_put_contents($path, $content);
    }

    /**
     * @param OutputInterface $output
     * @param string          $spaceId
     * @param string          $environmentId
     * @param string          $dir
     */
    private function writeReport(OutputInterface $output, string $spaceId, string $environmentId, string $dir)
    {
        $output->writeln(\sprintf(
            '<info>Result of content type classes generator for space "%s" and environment "%s".</info>',
            $spaceId,
            $environmentId
        ));
        $output->writeln('');

        if ($this->createdFiles) {
            $output->writeln(\sprintf('The following files were successfully generated:'));
            foreach ($this->createdFiles as $created) {
                $output->writeln(\sprintf(
                    '* <info>%s (%s)</info>
  - Entry <comment>%s/%s</comment>
  - Mapper <comment>%s/%s</comment>',
                    $created['content_type']->getId(),
                    $created['content_type']->getName(),
                    $dir,
                    $created['entry']['path'],
                    $dir,
                    $created['mapper']['path']
                ));
            }
            $output->writeln('');
        }

        $output->writeln(\sprintf(
            'Loader file generated at <comment>%s/_loader.php</comment>',
            $dir
        ));
    }
}
