<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Integration\Resource;

use Contentful\Management\ResourceBuilder;
use Contentful\Management\Resource\Organization;
use PHPUnit\Framework\TestCase;

class OrganizationTest extends TestCase
{
    /**
     * @expectedException \LogicException
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
     */
    public function testInvalidUpdate(Organization $organization)
    {
        (new ResourceBuilder())
            ->build(['sys' => [
                'type' => 'Organization',
            ]], $organization);
    }
}
