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
use Contentful\Management\Resource\Role;
use Contentful\Management\Resource\Role\Constraint\AndConstraint;
use Contentful\Management\Resource\Role\Constraint\EqualityConstraint;
use Contentful\Management\Resource\Role\Constraint\NotConstraint;
use Contentful\Management\Resource\Role\Constraint\OrConstraint;
use Contentful\Management\Resource\Role\Constraint\PathsConstraint;
use Contentful\Management\Resource\Role\Permissions;
use Contentful\Management\Resource\Role\Policy;
use Contentful\Tests\Management\BaseTestCase;

use function GuzzleHttp\json_encode as guzzle_json_encode;

class RoleTest extends BaseTestCase
{
    /**
     * @vcr e2e_role_get_one.json
     */
    public function testGetOne()
    {
        $proxy = $this->getReadOnlySpaceProxy();

        $role = $proxy->getRole('6khUMmsfVslYd7tRcThTgE');

        $this->assertSame('Developer', $role->getName());
        $this->assertSame('Allows reading Entries and managing API Keys', $role->getDescription());
        $this->assertLink('6khUMmsfVslYd7tRcThTgE', 'Role', $role->asLink());

        $policies = $role->getPolicies();
        $this->assertCount(2, $policies);
        $this->assertSame('allow', $policies[0]->getEffect());
        $this->assertSame(['read'], $policies[0]->getActions());
        $this->assertInstanceOf(AndConstraint::class, $policies[0]->getConstraint());
        $constraint = $policies[0]->getConstraint()->getChildren()[0];
        $this->assertInstanceOf(EqualityConstraint::class, $constraint);
        $this->assertSame('sys.type', $constraint->getDoc());
        $this->assertSame('Entry', $constraint->getValue());

        $permissions = $role->getPermissions();
        $this->assertSame('all', $permissions->getContentDelivery());
        $this->assertSame('read', $permissions->getContentModel());
        $this->assertNull($permissions->getSettings());
    }

    /**
     * @vcr e2e_role_get_collection.json
     */
    public function testGetCollection()
    {
        $proxy = $this->getReadOnlySpaceProxy();

        $roles = $proxy->getRoles();
        $role = $roles[0];

        $this->assertInstanceOf(Role::class, $roles[0]);

        // There should be just a few roles defined,
        // definitely below the pagination limit (100).
        // This check is useful to make sure that
        // all role objects were properly created.
        $this->assertCount($roles->getTotal(), $roles);

        $this->assertSame('Developer', $role->getName());
        $this->assertSame('Allows reading Entries and managing API Keys', $role->getDescription());

        $query = (new Query())
            ->setLimit(1)
        ;
        $roles = $proxy->getRoles($query);
        $role = $roles[0];

        $this->assertInstanceOf(Role::class, $role);
        $this->assertCount(1, $roles);

        $this->assertSame('Developer', $role->getName());
        $this->assertSame('Allows reading Entries and managing API Keys', $role->getDescription());
    }

    /**
     * @vcr e2e_role_create_update_delete.json
     */
    public function testCreateUpdateDelete()
    {
        $proxy = $this->getReadWriteSpaceProxy();

        $role = new Role('Custom role', 'This is a custom test role');

        $role->getPermissions()
            ->setContentDelivery('manage')
            ->setContentModel('manage')
            ->setSettings('manage')
        ;

        $policy = new Policy('allow', 'all');
        $role->addPolicy($policy);

        $constraint = new AndConstraint([
            new EqualityConstraint('sys.type', 'Entry'),
            new PathsConstraint('fields.%.%'),
            new OrConstraint([
                new EqualityConstraint('sys.type', 'Asset'),
                new NotConstraint(new EqualityConstraint('sys.type', 'ContentType')),
            ]),
        ]);
        $policy->setConstraint($constraint);

        $proxy->create($role);

        $this->assertNotNull($role->getId());
        $this->assertSame(0, $role->getSystemProperties()->getVersion());

        // The ResourceBuilder actually recreates from scratch all attributes,
        // using the result of the CMA response.
        // This is to make sure that all attributes were correctly recreated.
        $this->assertSame('Custom role', $role->getName());
        $this->assertSame('This is a custom test role', $role->getDescription());
        $policies = $role->getPolicies();
        $this->assertCount(1, $policies);
        $this->assertJsonStringEqualsJsonString(
            guzzle_json_encode($policy, \JSON_UNESCAPED_UNICODE),
            guzzle_json_encode($policies[0], \JSON_UNESCAPED_UNICODE)
        );

        // The API automatically converts permissions in "all",
        // if all permissions for a given attribute are set.
        // Therefore we create a new Permissions object with all attributes set to "all",
        // which is what we expect the new $role->getPermissions() will be.
        $permissions = (new Permissions())
            ->setContentDelivery('all')
            ->setContentModel('all')
            ->setSettings('all')
        ;
        $this->assertJsonStringEqualsJsonString(
            guzzle_json_encode($permissions, \JSON_UNESCAPED_UNICODE),
            guzzle_json_encode($role->getPermissions(), \JSON_UNESCAPED_UNICODE)
        );

        $secondPolicy = new Policy(
            'deny',
            ['create'],
            new EqualityConstraint('sys.type', 'ContentType')
        );
        $role->addPolicy($secondPolicy);

        $role->update();

        $this->assertSame(1, $role->getSystemProperties()->getVersion());
        $this->assertSame('Custom role', $role->getName());
        $this->assertSame('This is a custom test role', $role->getDescription());
        $this->assertCount(2, $role->getPolicies());
        $this->assertJsonStringEqualsJsonString(
            guzzle_json_encode([$policy, $secondPolicy], \JSON_UNESCAPED_UNICODE),
            guzzle_json_encode($role->getPolicies(), \JSON_UNESCAPED_UNICODE)
        );
        $this->assertJsonStringEqualsJsonString(
            guzzle_json_encode($permissions, \JSON_UNESCAPED_UNICODE),
            guzzle_json_encode($role->getPermissions(), \JSON_UNESCAPED_UNICODE)
        );

        $role->delete();
    }
}
