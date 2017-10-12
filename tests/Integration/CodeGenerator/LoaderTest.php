<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\Integration\Generator;

use Contentful\Management\CodeGenerator\Loader;
use Contentful\Management\Resource\ContentType;
use Contentful\Management\ResourceBuilder;
use Contentful\Management\SystemProperties;
use Contentful\Tests\Management\End2EndTestCase;

class LoaderTest extends End2EndTestCase
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
            'type' => 'contentType',
        ]));
        $property->setValue($contentType2, new SystemProperties([
            'id' => 'author',
            'type' => 'contentType',
        ]));

        $generator = new Loader('en-US');

        $code = $generator->generate([
            'namespace' => 'Contentful\\Tests\\Management\\Fixtures\\Integration\\CodeGenerator',
            'content_types' => [$contentType1, $contentType2],
        ]);

        $expected = \file_get_contents(__DIR__.'/../../Fixtures/Integration/CodeGenerator/_loader.php');

        $this->assertEquals($expected, $code);
    }

    /**
     * @depends testGenerator
     */
    public function testGenerateCodeWorks()
    {
        $builder = new ResourceBuilder();

        require __DIR__.'/../../Fixtures/Integration/CodeGenerator/_loader.php';
        $property = (new \ReflectionClass(ResourceBuilder::class))->getProperty('dataMapperMatchers');
        $property->setAccessible(true);
        $matchers = $property->getValue($builder);

        $this->assertInstanceOf(\Closure::class, $matchers['Entry']);

        $closure = $matchers['Entry'];
        $this->assertEquals(
            \Contentful\Tests\Management\Fixtures\Integration\CodeGenerator\Mapper\BlogPostMapper::class,
            $closure(['sys' => ['contentType' => ['sys' => ['id' => 'blogPost']]]])
        );
        $this->assertNull(
            $closure(['sys' => ['contentType' => ['sys' => ['id' => 'unrecognized']]]])
        );
    }
}
