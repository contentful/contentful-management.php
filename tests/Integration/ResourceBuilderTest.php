<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Integration;

use Contentful\Management\ResourceBuilder;
use Contentful\Management\Mapper\MapperInterface;
use PHPUnit\Framework\TestCase;

class ResourceBuilderTest extends TestCase
{
    /**
     * @expectedException \RuntimeException
     */
    public function testInexistentMapper()
    {
        $builder = new ResourceBuilder();
        $builder->setDataMapperMatcher('Asset', function (array $data) {
            return '\\Contentful\\Tests\\Integration\\InexistentAssetMapper';
        });

        $builder->build(['sys' => [
            'type' => 'Asset',
        ]]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInexistentSystemType()
    {
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

        $this->assertEquals(['sys' => ['type' => 'Asset']], $resource);
    }
}

class PassthroughMapper implements MapperInterface
{
    public function map($resource, array $data)
    {
        return $data;
    }
}
