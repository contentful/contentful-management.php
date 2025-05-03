<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource;

use Contentful\Core\Api\Link;
use Contentful\Management\Resource\SpaceMembership;
use Contentful\Tests\Management\BaseTestCase;

class SpaceMembershipTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $spaceMembership = new SpaceMembership();

        $spaceMembership->setEmail('php-cma-sdk-tests-eb2a4f5@contentful.com');
        $this->assertSame('php-cma-sdk-tests-eb2a4f5@contentful.com', $spaceMembership->getEmail());

        $spaceMembership->setAdmin(true);
        $this->assertTrue($spaceMembership->isAdmin());

        $this->assertEmpty($spaceMembership->getRoles());

        $role1 = new Link('6khUMmsfVslYd7tRcThTgE', 'Role');
        $role2 = new Link('6kj2AeUS0kJbHr0F2xCjIU', 'Role');
        $spaceMembership->addRole($role1);
        $this->assertSame([$role1], $spaceMembership->getRoles());

        $spaceMembership->addRoleLink('6kj2AeUS0kJbHr0F2xCjIU');
        $roles = $spaceMembership->getRoles();
        $this->assertCount(2, $roles);
        $this->assertLink('6khUMmsfVslYd7tRcThTgE', 'Role', $roles[0]);
        $this->assertSame($role1, $roles[0]);
        $this->assertLink('6kj2AeUS0kJbHr0F2xCjIU', 'Role', $roles[1]);
        $this->assertNotSame($role2, $roles[1]);

        $spaceMembership->setRoles([]);
        $this->assertSame([], $spaceMembership->getRoles());
        $spaceMembership->setRoles([$role1, $role2]);
        $this->assertSame([$role1, $role2], $spaceMembership->getRoles());

        $this->assertNull($spaceMembership->getUser());
    }

    public function testJsonSerialize()
    {
        $spaceMembership = (new SpaceMembership())
            ->setEmail('php-cma-sdk-tests-eb2a4f5@contentful.com')
            ->setAdmin(false)
            ->addRole(new Link('6khUMmsfVslYd7tRcThTgE', 'Role'))
        ;

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/space_membership.json', $spaceMembership);
    }
}
