<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Integration\Management\Resource;

use Contentful\Management\Resource\Organization;
use Contentful\Management\ResourceBuilder;
use PHPUnit\Framework\TestCase;

class OrganizationTest extends TestCase
{
    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Class "Contentful\Management\Resource\Organization" can only be instantiated as a result of an API call, manual creation is not allowed.
     */
    public function testInvalidCreation()
    {
        new Organization();
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

        $json = '{"sys":{"type":"Organization"},"name":"Test Org"}';

        $this->assertJsonStringEqualsJsonString($json, json_encode($organization));

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
