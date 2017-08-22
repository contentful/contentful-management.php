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
use Contentful\Management\Resource\Role;
use Contentful\Management\Role\Constraint\AndConstraint;
use Contentful\Management\Role\Constraint\EqualityConstraint;
use Contentful\Management\Role\Constraint\NotConstraint;
use Contentful\Management\Role\Constraint\OrConstraint;
use Contentful\Management\Role\Permissions;
use Contentful\Management\Role\Policy;
use Contentful\Tests\End2EndTestCase;

class RoleTest extends End2EndTestCase
{
    /**
     * @vcr e2e_role_get_one.json
     */
    public function testGetRole()
    {
        $manager = $this->getReadWriteSpaceManager();

        $role = $manager->getRole('6khUMmsfVslYd7tRcThTgE');

        $this->assertEquals('Developer', $role->getName());
        $this->assertEquals('Allows reading Entries and managing API Keys', $role->getDescription());
        $this->assertEquals(new Link('6khUMmsfVslYd7tRcThTgE', 'Role'), $role->asLink());

        $policies = $role->getPolicies();
        $this->assertCount(2, $policies);
        $this->assertEquals('allow', $policies[0]->getEffect());
        $this->assertEquals(['read'], $policies[0]->getActions());
        $this->assertInstanceOf(AndConstraint::class, $policies[0]->getConstraint());
        $constraint = $policies[0]->getConstraint()->getChildren()[0];
        $this->assertInstanceOf(EqualityConstraint::class, $constraint);
        $this->assertEquals('sys.type', $constraint->getDoc());
        $this->assertEquals('Entry', $constraint->getValue());

        $permissions = $role->getPermissions();
        $this->assertEquals('all', $permissions->getContentDelivery());
        $this->assertEquals(['read'], $permissions->getContentModel());
        $this->assertEquals([], $permissions->getSettings());
    }

    /**
     * @vcr e2e_role_get_collection.json
     */
    public function testGetRoles()
    {
        $manager = $this->getReadWriteSpaceManager();

        $roles = $manager->getRoles();
        $role = $roles[0];

        $this->assertInstanceOf(Role::class, $roles[0]);

        // There should be just a few roles defined,
        // definitely below the pagination limit (100).
        // This check is useful to make sure that
        // all role objects were properly created.
        $this->assertCount($roles->getTotal(), $roles);

        $this->assertEquals('Developer', $role->getName());
        $this->assertEquals('Allows reading Entries and managing API Keys', $role->getDescription());

        $query = (new Query())
            ->setLimit(1);
        $roles = $manager->getRoles($query);
        $role = $roles[0];

        $this->assertInstanceOf(Role::class, $role);
        $this->assertCount(1, $roles);

        $this->assertEquals('Developer', $role->getName());
        $this->assertEquals('Allows reading Entries and managing API Keys', $role->getDescription());
    }

    /**
     * @vcr e2e_role_create_update_delete.json
     */
    public function testCreateUpdateDelete()
    {
        $manager = $this->getReadWriteSpaceManager();

        $role = new Role('Custom role', 'This is a custom test role');

        $role->getPermissions()
            ->setContentDelivery(['read', 'manage'])
            ->setContentModel(['read', 'manage'])
            ->setSettings(['manage']);

        $policy = new Policy('allow', 'all');
        $role->addPolicy($policy);

        $constraint = new AndConstraint([
            new EqualityConstraint('sys.type', 'Entry'),
            new OrConstraint([
                new EqualityConstraint('sys.type', 'Asset'),
                new NotConstraint(new EqualityConstraint('sys.type', 'ContentType')),
            ]),
        ]);
        $policy->setConstraint($constraint);

        $manager->create($role);

        $this->assertNotNull($role->getSystemProperties()->getId());
        $this->assertEquals(0, $role->getSystemProperties()->getVersion());

        // The ResourceBuilder actually recreates from scratch all attributes,
        // using the result of the CMA response.
        // This is to make sure that all attributes were correctly recreated.
        $this->assertEquals('Custom role', $role->getName());
        $this->assertEquals('This is a custom test role', $role->getDescription());
        $this->assertCount(1, $role->getPolicies());
        $this->assertEquals([$policy], $role->getPolicies());

        // The API automatically converts permissions in "all",
        // if all permissions for a given attribute are set.
        // Therefore we create a new Permissions object with all attributes set to "all",
        // which is what we expect the new $role->getPermissions() will be.
        $permissions = (new Permissions())
            ->setContentDelivery('all')
            ->setContentModel('all')
            ->setSettings('all');
        $this->assertEquals($permissions, $role->getPermissions());

        $secondPolicy = new Policy(
            'deny',
            ['create'],
            new EqualityConstraint('sys.type', 'ContentType')
        );
        $role->addPolicy($secondPolicy);

        $manager->update($role);

        $this->assertEquals(1, $role->getSystemProperties()->getVersion());
        $this->assertEquals('Custom role', $role->getName());
        $this->assertEquals('This is a custom test role', $role->getDescription());
        $this->assertCount(2, $role->getPolicies());
        $this->assertEquals([$policy, $secondPolicy], $role->getPolicies());
        $this->assertEquals($permissions, $role->getPermissions());

        $manager->delete($role);
    }
}
