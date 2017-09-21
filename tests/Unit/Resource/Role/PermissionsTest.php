<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Management\Resource\Role;

use Contentful\Management\Resource\Role\Permissions;
use PHPUnit\Framework\TestCase;

class PermissionsTest extends TestCase
{
    public function testGetSetData()
    {
        $permissions = new Permissions();

        $permissions->setContentDelivery('all');
        $this->assertEquals('all', $permissions->getContentDelivery());

        $permissions->setContentDelivery('manage');
        $this->assertEquals('manage', $permissions->getContentDelivery());

        try {
            $permissions->setContentDelivery('invalid');
            $this->fail('Invalid ContentDelivery should throw an exception.');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Parameter $access in Permissions::setContentDelivery() must be either null or one of "read", "manage", "all", "invalid" given.', $e->getMessage());
        }

        $permissions->setContentModel('all');
        $this->assertEquals('all', $permissions->getContentModel());

        $permissions->setContentModel('manage');
        $this->assertEquals('manage', $permissions->getContentModel());

        try {
            $permissions->setContentModel('invalid');
            $this->fail('Invalid ContentModel should throw an exception.');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Parameter $access in Permissions::setContentModel() must be either null or one of "read", "manage", "all", "invalid" given.', $e->getMessage());
        }

        $permissions->setSettings('all');
        $this->assertEquals('all', $permissions->getSettings());

        $permissions->setSettings('manage');
        $this->assertEquals('manage', $permissions->getSettings());

        try {
            $permissions->setSettings('read');
            $this->fail('Invalid Settings should throw an exception.');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Parameter $access in Permissions::setSettings() must be either null or one of "manage", "all", "read" given.', $e->getMessage());
        }
    }

    public function testJsonSerialize()
    {
        $permissions = (new Permissions())
            ->setContentDelivery('all')
            ->setContentModel('all')
            ->setSettings('all');

        $json = '{"ContentDelivery":"all","ContentModel":"all","Settings":"all"}';
        $this->assertJsonStringEqualsJsonString($json, json_encode($permissions));
    }
}
