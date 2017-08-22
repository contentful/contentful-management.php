<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Resource;

use Contentful\Link;
use Contentful\Management\Resource\SpaceMembership;
use PHPUnit\Framework\TestCase;

class SpaceMembershipTest extends TestCase
{
    public function testGetSetData()
    {
        $spaceMembership = new SpaceMembership();

        $spaceMembership->setEmail('php-cma-sdk-tests-eb2a4f5@contentful.com');
        $this->assertEquals('php-cma-sdk-tests-eb2a4f5@contentful.com', $spaceMembership->getEmail());

        $spaceMembership->setAdmin(true);
        $this->assertTrue($spaceMembership->isAdmin());

        $this->assertEmpty($spaceMembership->getRoles());

        $role1 = new Link('6khUMmsfVslYd7tRcThTgE', 'Role');
        $role2 = new Link('6kj2AeUS0kJbHr0F2xCjIU', 'Role');
        $spaceMembership->addRole($role1);
        $this->assertEquals([$role1], $spaceMembership->getRoles());
        $spaceMembership->setRoles([$role1, $role2]);
        $this->assertEquals([$role1, $role2], $spaceMembership->getRoles());

        $this->assertNull($spaceMembership->getUser());
    }

    public function testJsonSerialize()
    {
        $spaceMembership = (new SpaceMembership())
            ->setEmail('php-cma-sdk-tests-eb2a4f5@contentful.com')
            ->setAdmin(false)
            ->addRole(new Link('6khUMmsfVslYd7tRcThTgE', 'Role'));

        $json = '{"sys":{"type":"SpaceMembership"},"email":"php-cma-sdk-tests-eb2a4f5@contentful.com","admin":false,"roles":[{"sys":{"type":"Link","linkType":"Role","id":"6khUMmsfVslYd7tRcThTgE"}}]}';

        $this->assertJsonStringEqualsJsonString($json, json_encode($spaceMembership));
    }
}
