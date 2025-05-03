<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource;

use Contentful\Management\Resource\Role;
use Contentful\Management\Resource\Role\Constraint\AndConstraint;
use Contentful\Management\Resource\Role\Constraint\EqualityConstraint;
use Contentful\Management\Resource\Role\Constraint\NotConstraint;
use Contentful\Management\Resource\Role\Constraint\OrConstraint;
use Contentful\Management\Resource\Role\Constraint\PathsConstraint;
use Contentful\Management\Resource\Role\Permissions;
use Contentful\Management\Resource\Role\Policy;
use Contentful\Tests\Management\BaseTestCase;

class RoleTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $role = new Role();

        $role->setName('Custom role');
        $this->assertSame('Custom role', $role->getName());

        $role->setDescription('This is a custom test role');
        $this->assertSame('This is a custom test role', $role->getDescription());

        $this->assertInstanceOf(Permissions::class, $role->getPermissions());

        $policy = new Policy('allow', 'all');
        $role->addPolicy($policy);
        $this->assertSame([$policy], $role->getPolicies());
        $role->setPolicies([$policy]);
        $this->assertSame([$policy], $role->getPolicies());
    }

    public function testJsonSerialize()
    {
        $role = new Role('Custom role', 'This is a custom test role');

        $role->addPolicy(new Policy(
            'allow',
            'all',
            new AndConstraint([
                new EqualityConstraint('sys.type', 'Entry'),
                new PathsConstraint('fields.%.%'),
                new OrConstraint([
                    new EqualityConstraint('sys.type', 'Asset'),
                    new NotConstraint(new EqualityConstraint('sys.type', 'ContentType')),
                ]),
            ])
        ));

        $role->getPermissions()
            ->setContentDelivery('manage')
            ->setContentModel('read')
            ->setSettings('all')
        ;

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/role.json', $role);
    }
}
