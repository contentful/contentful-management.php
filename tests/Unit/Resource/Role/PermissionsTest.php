<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\Role;

use Contentful\Management\Resource\Role\Permissions;
use Contentful\Tests\Management\BaseTestCase;

class PermissionsTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $permissions = new Permissions();

        $permissions->setContentDelivery('all');
        $this->assertSame('all', $permissions->getContentDelivery());

        $permissions->setContentDelivery('manage');
        $this->assertSame('manage', $permissions->getContentDelivery());

        try {
            $permissions->setContentDelivery('invalid');
            $this->fail('Invalid ContentDelivery should throw an exception.');
        } catch (\InvalidArgumentException $exception) {
            $this->assertSame('Parameter $access in Permissions::setContentDelivery() must be either null or one of "read", "manage", "all", "invalid" given.', $exception->getMessage());
        }

        $permissions->setContentModel('all');
        $this->assertSame('all', $permissions->getContentModel());

        $permissions->setContentModel('manage');
        $this->assertSame('manage', $permissions->getContentModel());

        try {
            $permissions->setContentModel('invalid');
            $this->fail('Invalid ContentModel should throw an exception.');
        } catch (\InvalidArgumentException $exception) {
            $this->assertSame('Parameter $access in Permissions::setContentModel() must be either null or one of "read", "manage", "all", "invalid" given.', $exception->getMessage());
        }

        $permissions->setSettings('all');
        $this->assertSame('all', $permissions->getSettings());

        $permissions->setSettings('manage');
        $this->assertSame('manage', $permissions->getSettings());

        try {
            $permissions->setSettings('read');
            $this->fail('Invalid Settings should throw an exception.');
        } catch (\InvalidArgumentException $exception) {
            $this->assertSame('Parameter $access in Permissions::setSettings() must be either null or one of "manage", "all", "read" given.', $exception->getMessage());
        }
    }

    public function testJsonSerialize()
    {
        $permissions = (new Permissions())
            ->setContentDelivery('all')
            ->setContentModel('all')
            ->setSettings('all')
        ;

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/Role/permissions.json', $permissions);
    }
}
