<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Integration\CodeGenerator;

use Contentful\Core\ResourceBuilder\BaseResourceBuilder;
use Contentful\Management\CodeGenerator\Loader;
use Contentful\Management\Resource\ContentType;
use Contentful\Management\ResourceBuilder;
use Contentful\Management\SystemProperties\ContentType as SystemProperties;
use Contentful\Tests\Management\BaseTestCase;

class LoaderTest extends BaseTestCase
{
    public function testGenerator()
    {
        $contentType1 = new ContentType('Blog Post');
        $contentType2 = new ContentType('Author');

        // The generator works with the system ID, which is not usually accessible,
        // hence this hack
        $property = (new \ReflectionClass(ContentType::class))->getProperty('sys');
        $property->setAccessible(true);
        $property->setValue($contentType1, new SystemProperties([
            'id' => 'blogPost',
            'type' => 'ContentType',
            'createdAt' => '2018-01-01T12:00:00.123Z',
            'updatedAt' => '2018-01-01T12:00:00.123Z',
            'createdBy' => [
                'sys' => [
                    'type' => 'Link',
                    'linkType' => 'User',
                    'id' => 'irrelevant',
                ],
            ],
            'updatedBy' => [
                'sys' => [
                    'type' => 'Link',
                    'linkType' => 'User',
                    'id' => 'irrelevant',
                ],
            ],
            'version' => 1,
            'space' => [
                'sys' => [
                    'type' => 'Link',
                    'linkType' => 'Space',
                    'id' => 'irrelevant',
                ],
            ],
            'environment' => [
                'sys' => [
                    'type' => 'Link',
                    'linkType' => 'Environment',
                    'id' => 'irrelevant',
                ],
            ],
        ]));
        $property->setValue($contentType2, new SystemProperties([
            'id' => 'author',
            'type' => 'ContentType',
            'createdAt' => '2018-01-01T12:00:00.123Z',
            'updatedAt' => '2018-01-01T12:00:00.123Z',
            'createdBy' => [
                'sys' => [
                    'type' => 'Link',
                    'linkType' => 'User',
                    'id' => 'irrelevant',
                ],
            ],
            'updatedBy' => [
                'sys' => [
                    'type' => 'Link',
                    'linkType' => 'User',
                    'id' => 'irrelevant',
                ],
            ],
            'version' => 1,
            'space' => [
                'sys' => [
                    'type' => 'Link',
                    'linkType' => 'Space',
                    'id' => 'irrelevant',
                ],
            ],
            'environment' => [
                'sys' => [
                    'type' => 'Link',
                    'linkType' => 'Environment',
                    'id' => 'irrelevant',
                ],
            ],
        ]));

        $generator = new Loader('en-US');

        $code = $generator->generate([
            'namespace' => 'Contentful\\Tests\\Management\\Fixtures\\Integration\\CodeGenerator',
            'content_types' => [$contentType1, $contentType2],
        ]);

        $expected = \file_get_contents(__DIR__.'/../../Fixtures/Integration/CodeGenerator/_loader.php');

        $this->assertSame($expected, $code);
    }

    /**
     * @depends testGenerator
     */
    public function testGenerateCodeWorks()
    {
        $builder = new ResourceBuilder();

        require __DIR__.'/../../Fixtures/Integration/CodeGenerator/_loader.php';
        $property = (new \ReflectionClass(BaseResourceBuilder::class))->getProperty('dataMapperMatchers');
        $property->setAccessible(true);
        $matchers = $property->getValue($builder);

        $this->assertInstanceOf(\Closure::class, $matchers['Entry']);

        $closure = $matchers['Entry'];
        $this->assertSame(
            \Contentful\Tests\Management\Fixtures\Integration\CodeGenerator\Mapper\BlogPostMapper::class,
            $closure(['sys' => ['contentType' => ['sys' => ['id' => 'blogPost']]]])
        );
        $this->assertNull(
            $closure(['sys' => ['contentType' => ['sys' => ['id' => 'unrecognized']]]])
        );
    }
}
