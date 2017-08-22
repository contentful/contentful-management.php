<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\E2E;

use Contentful\Link;
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
        $this->assertEquals(new Link('4Q3Lza73mxcjmluLU7V5EG', 'Organization'), $organization->asLink());
        $this->assertEquals(1, $sys->getVersion());
        $this->assertEquals(new \DateTimeImmutable('2017-07-12T13:04:54'), $sys->getCreatedAt());
        $this->assertEquals(new \DateTimeImmutable('2017-07-13T09:35:27'), $sys->getUpdatedAt());
    }

    /**
     * @vcr e2e_organization_get_collection_with_filter.json
     */
    public function testGetOrganizationsWithQuery()
    {
        $query = (new Query())
            ->setLimit(3);
        $organizations = $this->client->getOrganizations($query);

        $this->assertCount(3, $organizations);

        $organization = $organizations[2];

        $this->assertEquals('Contentful PHP SDK Testing', $organization->getName());
        $this->assertEquals('4Q3Lza73mxcjmluLU7V5EG', $organization->getSystemProperties()->getId());
    }
}
