<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Console\Command;

use Contentful\Management\Client;
use Contentful\Management\Generator\ClassesGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildContentTypesCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('build:content-types')
            ->setDefinition([
                new InputArgument(
                    'space-id',
                    InputArgument::REQUIRED,
                    'ID of the space to use.'
                ),
                new InputArgument(
                    'token',
                    InputArgument::REQUIRED,
                    'Token to access the space.'
                ),
                new InputArgument(
                    'dir',
                    InputArgument::REQUIRED,
                    'The directory to write the cache to.'
                ),
                new InputArgument(
                    'namespace',
                    InputArgument::OPTIONAL,
                    'The base namespace to use.',
                    ''
                ),
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $spaceId = $input->getArgument('space-id');
        $dir = $input->getArgument('dir');

        $client = new Client(
            $input->getArgument('token'),
            $spaceId
        );
        $generator = new ClassesGenerator($client);

        $files = $generator->generate($input->getArgument('namespace'));

        $this->writeFiles($dir, $files);

        $output->writeln(sprintf('<info>Result of content type classes generation for space "%s"</info>', $spaceId));
        $output->writeln('');

        if ($files['created']) {
            $output->writeln(sprintf('The following files were successfully generated:'));
            foreach ($files['created'] as $contentTypeId => $created) {
                $output->writeln(\sprintf(
                    '* <info>%s</info>
  - Entry <comment>%s/%s</comment>
  - Mapper <comment>%s/%s</comment>',
                    $contentTypeId,
                    $dir,
                    $created['entry']['path'],
                    $dir,
                    $created['mapper']['path']
                ));
            }
            $output->writeln('');
        }

        $output->writeln(\sprintf('Loader file generated at <comment>%s/%s</comment>', $dir, $files['loader']['path']));
    }

    /**
     * @param string $dir
     * @param array  $files
     */
    private function writeFiles(string $dir, array $files)
    {
        foreach ($files['created'] as $content) {
            $this->writeFile(
                $dir.'/'.$content['entry']['path'],
                $content['entry']['content']
            );
            $this->writeFile(
                $dir.'/'.$content['mapper']['path'],
                $content['mapper']['content']
            );
        }

        $this->writeFile(
            $dir.'/'.$files['loader']['path'],
            $files['loader']['content']
        );
    }

    /**
     * @param string $path
     * @param string $content
     *
     * @return bool
     */
    private function writeFile(string $path, string $content)
    {
        if (!\is_dir(\dirname($path))) {
            \mkdir(\dirname($path), 0777, true);
        }

        \file_put_contents($path, $content);
    }
}
