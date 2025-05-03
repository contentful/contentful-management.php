<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Management\Query;
use Contentful\Tests\Management\BaseTestCase;

class OrganizationTest extends BaseTestCase
{
    /**
     * @vcr e2e_organization_get_collection.json
     */
    public function testGetCollection()
    {
        $organizations = $this->getClient()->getOrganizations();

        $this->assertCount(3, $organizations);

        $organization = $organizations[2];
        $this->assertSame('Contentful PHP SDK Testing', $organization->getName());

        $sys = $organization->getSystemProperties();
        $this->assertSame('Organization', $sys->getType());
        $this->assertSame('4Q3Lza73mxcjmluLU7V5EG', $sys->getId());
        $this->assertLink('4Q3Lza73mxcjmluLU7V5EG', 'Organization', $organization->asLink());
        $this->assertSame(1, $sys->getVersion());
        $this->assertSame('2017-07-12T13:04:54Z', (string) $sys->getCreatedAt());
        $this->assertSame('2017-07-13T09:35:27Z', (string) $sys->getUpdatedAt());

        $this->assertSame([], $organization->asUriParameters());

        $query = (new Query())
            ->setLimit(3)
        ;
        $organizations = $this->getClient()->getOrganizations($query);

        $this->assertCount(3, $organizations);

        $organization = $organizations[2];

        $this->assertSame('Contentful PHP SDK Testing', $organization->getName());
        $this->assertSame('4Q3Lza73mxcjmluLU7V5EG', $organization->getId());
    }
}
