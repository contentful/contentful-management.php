<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Generator;

use Contentful\Management\Client;
use Contentful\Management\Query;
use Contentful\Management\Resource\ContentType;
use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\PrettyPrinter\Standard as StandardPrettyPrinter;

/**
 * ClassesGenerator class.
 */
class ClassesGenerator
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var EntryGenerator
     */
    private $entryGenerator;

    /**
     * @var MapperGenerator
     */
    private $mapperGenerator;

    /**
     * ClassesGenerator constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $defaultLocale = 'en-US';

        $locales = $this->client->locale->getAll();
        foreach ($locales as $locale) {
            if ($locale->isDefault()) {
                $defaultLocale = $locale->getCode();

                break;
            }
        }

        $this->entryGenerator = new EntryGenerator($defaultLocale);
        $this->mapperGenerator = new MapperGenerator($defaultLocale);
    }

    /**
     * @param string $namespace
     *
     * @return array
     */
    public function generate(string $namespace): array
    {
        $contentTypes = $this->loadAllContentTypes();
        $files = [
            'created' => [],
            'loader' => [
                'path' => '_loader.php',
                'content' => $this->generateLoader($contentTypes, $namespace),
            ],
        ];

        foreach ($contentTypes as $contentType) {
            $contentTypeId = $contentType->getId();

            $files['created'][$contentTypeId] = [
                'entry' => [
                    'path' => $this->generateEntryPath($contentType),
                    'content' => $this->entryGenerator->generate($contentType, $namespace),
                ],
                'mapper' => [
                    'path' => $this->generateMapperPath($contentType),
                    'content' => $this->mapperGenerator->generate($contentType, $namespace),
                ],
            ];
        }

        return $files;
    }

    /**
     * @param ContentType[] $contentTypes
     * @param string        $namespace
     *
     * @return string
     */
    private function generateLoader(array $contentTypes, string $namespace): string
    {
        $classParts = \array_filter(\explode('\\', $namespace));

        $cases = [];

        foreach ($contentTypes as $contentType) {
            $contentTypeParts = $classParts;
            $contentTypeParts[] = 'Mapper';
            $contentTypeParts[] = $this->convertToStudlyCaps($contentType->getId()).'Mapper';

            $cases[] = new Node\Stmt\Case_(
                new Node\Scalar\String_($contentType->getId()),
                [
                    new Node\Stmt\Return_(
                        new Node\Expr\ClassConstFetch(
                            new Node\Name\FullyQualified($contentTypeParts),
                            'class'
                        )
                    ),
                ]
            );
        }

        $method = new Node\Expr\MethodCall(
            new Node\Expr\Variable('builder'),
            'setDataMapperMatcher',
            [
                new Node\Arg(
                    new Node\Scalar\String_('Entry')
                ),
                new Node\Arg(
                    new Node\Expr\Closure([
                        'params' => [new Node\Param('data', null, 'array')],
                        'stmts' => [
                            new Node\Stmt\Switch_(
                                new Node\Expr\ArrayDimFetch(
                                    new Node\Expr\ArrayDimFetch(
                                        new Node\Expr\ArrayDimFetch(
                                            new Node\Expr\ArrayDimFetch(
                                                new Node\Expr\Variable('data'),
                                                new Node\Scalar\String_('sys')
                                            ),
                                            new Node\Scalar\String_('contentType')
                                        ),
                                        new Node\Scalar\String_('sys')
                                    ),
                                    new Node\Scalar\String_('id')
                                ),
                                $cases
                            ),
                        ],
                    ])
                ),
            ],
            [
                'comments' => [
                    new Comment('// You can include this file in your code or simply copy/paste it'."\n".'// for configuring the active ResourceBuilder object'),
                ],
            ]
        );

        $prettyPrinter = new StandardPrettyPrinter([
            'shortArraySyntax' => true,
        ]);

        $code = $prettyPrinter->prettyPrintFile([$method]);

        return \preg_replace('/\n(\s+)\n/', "\n\n", $code)."\n";
    }

    /**
     * @return ContentType[]
     */
    private function loadAllContentTypes(): array
    {
        $skip = 0;
        $limit = 100;

        $allContentTypes = [];
        $query = (new Query())
            ->setLimit($limit);

        do {
            $query->setSkip($skip);
            $contentTypes = $this->client
                ->contentType
                ->getAll($query);
            $allContentTypes += $contentTypes->getItems();

            $skip += $limit;
        } while ($contentTypes->getTotal() > $skip);

        return $allContentTypes;
    }

    /**
     * @param ContentType $contentType
     *
     * @return string
     */
    private function generateEntryPath(ContentType $contentType): string
    {
        return $this->convertToStudlyCaps($contentType->getId()).'.php';
    }

    /**
     * @param ContentType $contentType
     *
     * @return string
     */
    private function generateMapperPath(ContentType $contentType): string
    {
        return 'Mapper/'.$this->convertToStudlyCaps($contentType->getId()).'Mapper.php';
    }

    /**
     * Converts a string to SutdlyCaps.
     *
     * @param string $name
     *
     * @return string
     */
    protected function convertToStudlyCaps(string $name): string
    {
        return \ucwords(\str_replace(['-', '_'], ' ', $name));
    }
}
