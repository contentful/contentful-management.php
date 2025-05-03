<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Integration;

use Contentful\Core\ResourceBuilder\MapperInterface;
use Contentful\Management\ResourceBuilder;
use Contentful\Tests\Management\BaseTestCase;

class ResourceBuilderTest extends BaseTestCase
{
    public function testInexistentMapper()
    {
        $this->expectException(\RuntimeException::class);

        $builder = new ResourceBuilder();
        $builder->setDataMapperMatcher('Asset', function (array $data) {
            return '\\Contentful\\Tests\\Integration\\InexistentAssetMapper';
        });

        $builder->build(['sys' => [
            'type' => 'Asset',
        ]]);
    }

    public function testInexistentSystemType()
    {
        $this->expectException(\InvalidArgumentException::class);

        $builder = new ResourceBuilder();

        $builder->build(['sys' => [
            'type' => 'UnsupportedType',
        ]]);
    }

    public function testCustomMapper()
    {
        $builder = new ResourceBuilder();
        $builder->setDataMapperMatcher('Asset', function (array $data) {
            return PassthroughMapper::class;
        });

        $resource = $builder->build(['sys' => [
            'type' => 'Asset',
        ]]);

        $this->assertSame(['sys' => ['type' => 'Asset']], $resource);
    }
}

class PassthroughMapper implements MapperInterface
{
    public function map($resource, array $data)
    {
        return $data;
    }
}
