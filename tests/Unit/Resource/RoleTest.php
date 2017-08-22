<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Resource;

use Contentful\Management\Resource\Role;
use Contentful\Management\Role\Constraint\AndConstraint;
use Contentful\Management\Role\Constraint\EqualityConstraint;
use Contentful\Management\Role\Constraint\NotConstraint;
use Contentful\Management\Role\Constraint\OrConstraint;
use Contentful\Management\Role\Permissions;
use Contentful\Management\Role\Policy;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public function testGetSetData()
    {
        $role = new Role();

        $role->setName('Custom role');
        $this->assertEquals('Custom role', $role->getName());

        $role->setDescription('This is a custom test role');
        $this->assertEquals('This is a custom test role', $role->getDescription());

        $this->assertEquals(new Permissions(), $role->getPermissions());

        $policy = new Policy('allow', 'all');
        $role->addPolicy($policy);
        $this->assertEquals([$policy], $role->getPolicies());
        $role->setPolicies([$policy]);
        $this->assertEquals([$policy], $role->getPolicies());
    }

    public function testJsonSerialize()
    {
        $role = new Role('Custom role', 'This is a custom test role');

        $role->addPolicy(new Policy(
            'allow',
            'all',
            new AndConstraint([
                new EqualityConstraint('sys.type', 'Entry'),
                new OrConstraint([
                    new EqualityConstraint('sys.type', 'Asset'),
                    new NotConstraint(new EqualityConstraint('sys.type', 'ContentType')),
                ]),
            ])
        ));

        $role->getPermissions()
            ->setContentDelivery(['read', 'manage'])
            ->setContentModel(['read'])
            ->setSettings('all');

        $json = '{"sys":{"type":"Role"},"name":"Custom role","description":"This is a custom test role","permissions":{"ContentDelivery":["read","manage"],"ContentModel":["read"],"Settings":"all"},"policies":[{"effect":"allow","actions":"all","constraint":{"and":[{"equals":[{"doc":"sys.type"},"Entry"]},{"or":[{"equals":[{"doc":"sys.type"},"Asset"]},{"not":[{"equals":[{"doc":"sys.type"},"ContentType"]}]}]}]}}]}';

        $this->assertJsonStringEqualsJsonString($json, json_encode($role));
    }
}
