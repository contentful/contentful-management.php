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
use Contentful\Management\Resource\SpaceMembership;
use Contentful\Tests\End2EndTestCase;

class SpaceMembershipTest extends End2EndTestCase
{
    /**
     * @vcr e2e_space_membership_get_one.json
     */
    public function testGetSpaceMembership()
    {
        $manager = $this->getReadWriteSpaceManager();

        $spaceMembership = $manager->getSpaceMembership('3pCRBWtdkT0HzQmQpRcitU');

        $this->assertNull($spaceMembership->getEmail());
        $this->assertNotNull($spaceMembership->getSystemProperties()->getId());
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
        $manager = $this->getReadWriteSpaceManager();

        $spaceMemberships = $manager->getSpaceMemberships();
        $spaceMembership = $spaceMemberships[1];

        $this->assertInstanceOf(SpaceMembership::class, $spaceMembership);

        // There should be just a few space memberships defined,
        // definitely below the pagination limit (100).
        // This check is useful to make sure that
        // all space memberships objects were properly created.
        $this->assertCount($spaceMemberships->getTotal(), $spaceMemberships);

        $this->assertNull($spaceMembership->getEmail());
        $this->assertNotNull($spaceMembership->getSystemProperties()->getId());
        $this->assertInstanceOf(Link::class, $spaceMembership->getUser());
        $this->assertEquals('4Q3e6duhma7V6czH7UXHzE', $spaceMembership->getUser()->getId());
        $this->assertEmpty($spaceMembership->getRoles());
        $this->assertTrue($spaceMembership->isAdmin());

        $query = (new Query())
            ->setLimit(2);
        $spaceMemberships = $manager->getSpaceMemberships($query);
        $spaceMembership = $spaceMemberships[1];

        $this->assertInstanceOf(SpaceMembership::class, $spaceMembership);

        // There should be just a few space memberships defined,
        // definitely below the pagination limit (100).
        // This check is useful to make sure that
        // all space memberships objects were properly created.
        $this->assertCount($spaceMemberships->getTotal(), $spaceMemberships);

        $this->assertNull($spaceMembership->getEmail());
        $this->assertNotNull($spaceMembership->getSystemProperties()->getId());
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
        $manager = $this->getReadWriteSpaceManager();

        $spaceMembership = new SpaceMembership();
        $spaceMembership
            ->setEmail('php-cma-sdk-tests-eb2a4f5@contentful.com')
            ->setAdmin(true);

        $manager->create($spaceMembership);

        $this->assertNull($spaceMembership->getEmail());
        $this->assertNotNull($spaceMembership->getSystemProperties()->getId());
        $this->assertInstanceOf(Link::class, $spaceMembership->getUser());
        $this->assertEquals('2ZEuONMmCXSeGjl2CryAaM', $spaceMembership->getUser()->getId());
        $this->assertEmpty($spaceMembership->getRoles());
        $this->assertTrue($spaceMembership->isAdmin());

        $role = new Link('6khUMmsfVslYd7tRcThTgE', 'Role');
        $spaceMembership
            ->setAdmin(false)
            ->addRole($role);

        $manager->update($spaceMembership);

        $this->assertNull($spaceMembership->getEmail());
        $this->assertNotNull($spaceMembership->getSystemProperties()->getId());
        $this->assertInstanceOf(Link::class, $spaceMembership->getUser());
        $this->assertEquals('2ZEuONMmCXSeGjl2CryAaM', $spaceMembership->getUser()->getId());
        $this->assertEquals([$role], $spaceMembership->getRoles());
        $this->assertFalse($spaceMembership->isAdmin());

        $manager->delete($spaceMembership);
    }
}
