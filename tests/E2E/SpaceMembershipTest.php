<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Core\Api\Link;
use Contentful\Management\Query;
use Contentful\Management\Resource\Role;
use Contentful\Management\Resource\SpaceMembership;
use Contentful\Tests\Management\BaseTestCase;

class SpaceMembershipTest extends BaseTestCase
{
    /**
     * @vcr e2e_space_membership_get_one.json
     */
    public function testGetOne()
    {
        $proxy = $this->getReadOnlySpaceProxy();

        $spaceMembership = $proxy->getSpaceMembership('3pCRBWtdkT0HzQmQpRcitU');

        $this->assertNull($spaceMembership->getEmail());
        $this->assertNotNull($spaceMembership->getId());
        $this->assertLink('4Q3e6duhma7V6czH7UXHzE', 'User', $spaceMembership->getUser());
        $this->assertInstanceOf(Link::class, $spaceMembership->getUser());
        $this->assertSame('4Q3e6duhma7V6czH7UXHzE', $spaceMembership->getUser()->getId());
        $this->assertEmpty($spaceMembership->getRoles());
        $this->assertTrue($spaceMembership->isAdmin());
    }

    /**
     * @vcr e2e_space_membership_get_collection.json
     */
    public function testGetCollection()
    {
        $proxy = $this->getReadOnlySpaceProxy();

        $spaceMemberships = $proxy->getSpaceMemberships();
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
        $this->assertSame('4Q3e6duhma7V6czH7UXHzE', $spaceMembership->getUser()->getId());
        $this->assertEmpty($spaceMembership->getRoles());
        $this->assertTrue($spaceMembership->isAdmin());

        $query = (new Query())
            ->setLimit(2)
        ;
        $spaceMemberships = $proxy->getSpaceMemberships($query);
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
        $this->assertSame('4Q3e6duhma7V6czH7UXHzE', $spaceMembership->getUser()->getId());
        $this->assertEmpty($spaceMembership->getRoles());
        $this->assertTrue($spaceMembership->isAdmin());
    }

    /**
     * @vcr e2e_space_membership_create_update_delete.json
     */
    public function testCreateUpdateDelete()
    {
        $proxy = $this->getReadWriteSpaceProxy();

        $spaceMembership = new SpaceMembership();
        $spaceMembership
            ->setEmail('php-cma-sdk-tests-eb2a4f5@contentful.com')
            ->setAdmin(true)
        ;

        $proxy->create($spaceMembership);

        $this->assertNull($spaceMembership->getEmail());
        $this->assertNotNull($spaceMembership->getId());
        $this->assertInstanceOf(Link::class, $spaceMembership->getUser());
        $this->assertSame('2ZEuONMmCXSeGjl2CryAaM', $spaceMembership->getUser()->getId());
        $this->assertEmpty($spaceMembership->getRoles());
        $this->assertTrue($spaceMembership->isAdmin());

        /** @var Role $role */
        $role = \array_reduce($proxy->getRoles()->getItems(), function ($carry, Role $role) {
            return $carry ?: ('Developer' === $role->getName() ? $role : null);
        });

        $spaceMembership
            ->setAdmin(false)
            ->addRole($role->asLink())
        ;

        $spaceMembership->update();

        $this->assertNull($spaceMembership->getEmail());
        $this->assertNotNull($spaceMembership->getId());
        $this->assertInstanceOf(Link::class, $spaceMembership->getUser());
        $this->assertSame('2ZEuONMmCXSeGjl2CryAaM', $spaceMembership->getUser()->getId());
        $this->assertLink($role->getId(), 'Role', $spaceMembership->getRoles()[0]);
        $this->assertFalse($spaceMembership->isAdmin());

        $spaceMembership->delete();
    }
}
