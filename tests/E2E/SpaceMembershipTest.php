<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\E2E\Management;

use Contentful\Link;
use Contentful\Management\Query;
use Contentful\Management\Resource\SpaceMembership;
use Contentful\Tests\End2EndTestCase;

class SpaceMembershipTest extends End2EndTestCase
{
    /**
     * @vcr e2e_space_membership_get_one.json
     */
    public function testGetSpaceMembership()
    {
        $client = $this->getReadWriteClient();

        $spaceMembership = $client->spaceMembership->get('3pCRBWtdkT0HzQmQpRcitU');

        $this->assertNull($spaceMembership->getEmail());
        $this->assertNotNull($spaceMembership->getId());
        $this->assertInstanceOf(Link::class, $spaceMembership->getUser());
        $this->assertEquals('4Q3e6duhma7V6czH7UXHzE', $spaceMembership->getUser()->getId());
        $this->assertEmpty($spaceMembership->getRoles());
        $this->assertTrue($spaceMembership->isAdmin());
    }

    /**
     * @vcr e2e_space_membership_get_collection.json
     */
    public function testGetSpaceMemberships()
    {
        $client = $this->getReadWriteClient();

        $spaceMemberships = $client->spaceMembership->getAll();
        $spaceMembership = $spaceMemberships[1];

        $this->assertInstanceOf(SpaceMembership::class, $spaceMembership);

        // There should be just a few space memberships defined,
        // definitely below the pagination limit (100).
        // This check is useful to make sure that
        // all space memberships objects were properly created.
        $this->assertCount($spaceMemberships->getTotal(), $spaceMemberships);

        $this->assertNull($spaceMembership->getEmail());
        $this->assertNotNull($spaceMembership->getId());
        $this->assertInstanceOf(Link::class, $spaceMembership->getUser());
        $this->assertEquals('4Q3e6duhma7V6czH7UXHzE', $spaceMembership->getUser()->getId());
        $this->assertEmpty($spaceMembership->getRoles());
        $this->assertTrue($spaceMembership->isAdmin());

        $query = (new Query())
            ->setLimit(2);
        $spaceMemberships = $client->spaceMembership->getAll($query);
        $spaceMembership = $spaceMemberships[1];

        $this->assertInstanceOf(SpaceMembership::class, $spaceMembership);

        // There should be just a few space memberships defined,
        // definitely below the pagination limit (100).
        // This check is useful to make sure that
        // all space memberships objects were properly created.
        $this->assertCount($spaceMemberships->getTotal(), $spaceMemberships);

        $this->assertNull($spaceMembership->getEmail());
        $this->assertNotNull($spaceMembership->getId());
        $this->assertInstanceOf(Link::class, $spaceMembership->getUser());
        $this->assertEquals('4Q3e6duhma7V6czH7UXHzE', $spaceMembership->getUser()->getId());
        $this->assertEmpty($spaceMembership->getRoles());
        $this->assertTrue($spaceMembership->isAdmin());
    }

    /**
     * @vcr e2e_space_membership_create_update_delete.json
     */
    public function testCreateUpdateDelete()
    {
        $client = $this->getReadWriteClient();

        $spaceMembership = new SpaceMembership();
        $spaceMembership
            ->setEmail('php-cma-sdk-tests-eb2a4f5@contentful.com')
            ->setAdmin(true);

        $client->spaceMembership->create($spaceMembership);

        $this->assertNull($spaceMembership->getEmail());
        $this->assertNotNull($spaceMembership->getId());
        $this->assertInstanceOf(Link::class, $spaceMembership->getUser());
        $this->assertEquals('2ZEuONMmCXSeGjl2CryAaM', $spaceMembership->getUser()->getId());
        $this->assertEmpty($spaceMembership->getRoles());
        $this->assertTrue($spaceMembership->isAdmin());

        $spaceMembership
            ->setAdmin(false)
            ->addRoleLink('6khUMmsfVslYd7tRcThTgE');

        $spaceMembership->update();

        $this->assertNull($spaceMembership->getEmail());
        $this->assertNotNull($spaceMembership->getId());
        $this->assertInstanceOf(Link::class, $spaceMembership->getUser());
        $this->assertEquals('2ZEuONMmCXSeGjl2CryAaM', $spaceMembership->getUser()->getId());
        $this->assertEquals([new Link('6khUMmsfVslYd7tRcThTgE', 'Role')], $spaceMembership->getRoles());
        $this->assertFalse($spaceMembership->isAdmin());

        $spaceMembership->delete();
    }
}
