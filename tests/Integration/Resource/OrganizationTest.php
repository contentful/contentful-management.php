<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\Integration\Resource;

use Contentful\Management\Resource\Organization;
use Contentful\Management\ResourceBuilder;
use Contentful\Tests\Management\BaseTestCase;

class OrganizationTest extends BaseTestCase
{
    /**
     * @expectedException \Error
     */
    public function testInvalidCreation()
    {
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

    /**
     * @return Organization
     */
    public function testJsonSerialize(): Organization
    {
        $organization = (new ResourceBuilder())->build([
            'sys' => [
                'type' => 'Organization',
            ],
            'name' => 'Test Org',
        ]);

        $this->assertJsonFixtureEqualsJsonObject('Integration/Resource/organization.json', $organization);

        return $organization;
    }

    /**
     * @param Organization $organization
     *
     * @depends testJsonSerialize
     * @expectedException \LogicException
     * @expectedExceptionMessage Trying to update resource object in mapper of type "Contentful\Management\Mapper\Organization", but only creation from scratch is supported.
     */
    public function testInvalidUpdate(Organization $organization)
    {
        (new ResourceBuilder())
            ->build(['sys' => [
                'type' => 'Organization',
            ]], $organization);
    }

    /**
     * @param Organization $organization
     *
     * @depends testJsonSerialize
     * @expectedException \LogicException
     * @expectedExceptionMessage Trying to convert object of class "Contentful\Management\Resource\Organization" to a request body format, but operation is not supported on this class.
     */
    public function testInvalidConversionToRequestBody(Organization $organization)
    {
        $organization->asRequestBody();
    }
}
