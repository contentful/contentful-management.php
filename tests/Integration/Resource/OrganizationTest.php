<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Integration\Resource;

use Contentful\Management\Resource\Organization;
use Contentful\Management\ResourceBuilder;
use Contentful\Tests\Management\BaseTestCase;

class OrganizationTest extends BaseTestCase
{
    public function testInvalidCreation()
    {
        $this->expectException(\Error::class);
        new Organization();
    }

    /**
     * This test is meaningless.
     * It's only here because private, empty constructors are not correctly picked by code coverage.
     */
    public function testConstructor()
    {
        $class = new \ReflectionClass(Organization::class);
        $object = $class->newInstanceWithoutConstructor();
        $constructor = $class->getConstructor();
        $constructor->setAccessible(true);
        $constructor->invoke($object);

        $this->markTestAsPassed();
    }

    public function testJsonSerialize(): Organization
    {
        $resource = (new ResourceBuilder())
            ->build($this->getParsedFixture('serialize.json'))
        ;

        $this->assertJsonFixtureEqualsJsonObject('Integration/Resource/Organization/serialize.json', $resource);

        return $resource;
    }

    /**
     * @depends testJsonSerialize
     */
    public function testInvalidUpdate(Organization $organization)
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Trying to update resource object in mapper of type \"Contentful\Management\Mapper\Organization\", but only creation from scratch is supported.");

        (new ResourceBuilder())
            ->build(['sys' => [
                'type' => 'Organization',
            ]], $organization)
        ;
    }

    /**
     * @depends testJsonSerialize
     */
    public function testInvalidConversionToRequestBody(Organization $organization)
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Trying to convert object of class \"Contentful\Management\Resource\Organization\" to a request body format, but operation is not supported on this class.");

        $organization->asRequestBody();
    }
}
