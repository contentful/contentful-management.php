<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\Unit\Resource;

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

    public function testJsonSerialize()
    {
        $organization = (new ResourceBuilder())->buildObjectsFromRawData([
            'sys' => [
                'type' => 'Organization',
            ],
            'name' => 'Test Org',
        ]);

        $json = '{"sys":{"type":"Organization"},"name":"Test Org"}';

        $this->assertJsonStringEqualsJsonString($json, json_encode($organization));
    }
}
