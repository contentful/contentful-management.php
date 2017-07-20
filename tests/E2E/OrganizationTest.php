<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\E2E;

use Contentful\Management\Query;
use Contentful\Tests\End2EndTestCase;

class OrganizationTest extends End2EndTestCase
{
    /**
     * @vcr e2e_organization_get_collection.json
     */
    public function testGetOrganizations()
    {
        $organizations = $this->client->getOrganizations();

        $this->assertCount(3, $organizations);

        $organization = $organizations[2];
        $this->assertEquals('Contentful PHP SDK Testing', $organization->getName());

        $sys = $organization->getSystemProperties();
        $this->assertEquals('Organization', $sys->getType());
        $this->assertEquals('4Q3Lza73mxcjmluLU7V5EG', $sys->getId());
        $this->assertEquals(1, $sys->getVersion());
        $this->assertEquals(new \DateTimeImmutable('2017-07-12T13:04:54'), $sys->getCreatedAt());
        $this->assertEquals(new \DateTimeImmutable('2017-07-13T09:35:27'), $sys->getUpdatedAt());

        $this->assertJsonStringEqualsJsonString('{"sys":{"id":"4Q3Lza73mxcjmluLU7V5EG","type":"Organization","createdAt":"2017-07-12T13:04:54Z","updatedAt":"2017-07-13T09:35:27Z","version":1},"name":"Contentful PHP SDK Testing"}', json_encode($organization));
    }

    /**
     * @vcr e2e_organization_get_collection_with_filter.json
     */
    public function testGetOrganizationsWithQuery()
    {
        $query = (new Query())
            ->setLimit(1);
        $organizations = $this->client->getOrganizations($query);

        $this->assertCount(1, $organizations);
    }
}
