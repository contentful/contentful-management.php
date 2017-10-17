<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\Role;

use Contentful\Management\Resource\Role\Constraint\EqualityConstraint;
use Contentful\Management\Resource\Role\Policy;
use Contentful\Tests\Management\BaseTestCase;

class PolicyTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $policy = new Policy('allow');
        // These are the possible values
        $policy->setEffect('deny');
        $this->assertEquals('deny', $policy->getEffect());

        try {
            $policy->setEffect('invalid');
            $this->fail('Invalid effect should throw an exception.');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Parameter "$effect" in "Policy::setEffect()" must have either the value "allow" or "deny", "invalid" given.', $e->getMessage());
        }

        try {
            $policy->setActions('invalid');
            $this->fail('Invalid actions should throw an exception.');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Argument "$actions" in "Policy::setActions()" must be either a string "all", or an array containing a subset of these values: read, create, update, delete, publish, unpublish, archive, unarchive.', $e->getMessage());
        }

        $policy->setActions('all');

        try {
            $policy->addAction('read');
            $this->fail('Action can not be added when it is currently configured as "all".');
        } catch (\LogicException $e) {
            $this->assertEquals('Trying to add an action to a set, but the current value is a string. Use "Policy::setActions()" to initialize to an array.', $e->getMessage());
        }

        $policy->setActions([]);

        try {
            $policy->addAction('invalid');
            $this->fail('Invalid action should throw an exception.');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Argument "$action" in "Policy::addAction()" must be one of these values: read, create, update, delete, publish, unpublish, archive, unarchive.', $e->getMessage());
        }

        $policy->setActions([]);
        $policy->addAction('read');
        $this->assertEquals(['read'], $policy->getActions());

        $policy->setConstraint(null);
        $this->assertNull($policy->getConstraint());
    }

    public function testJsonSerialize()
    {
        $policy = new Policy('allow', 'all', new EqualityConstraint('sys.type', 'Entry'));

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/Role/policy.json', $policy);
    }
}
