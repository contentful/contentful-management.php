<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
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
        $this->assertSame('deny', $policy->getEffect());

        try {
            $policy->setEffect('invalid');
            $this->fail('Invalid effect should throw an exception.');
        } catch (\InvalidArgumentException $exception) {
            $this->assertSame('Parameter "$effect" in "Policy::setEffect()" must have either the value "allow" or "deny", "invalid" given.', $exception->getMessage());
        }

        try {
            $policy->setActions('invalid');
            $this->fail('Invalid actions should throw an exception.');
        } catch (\InvalidArgumentException $exception) {
            $this->assertSame('Argument "$actions" in "Policy::setActions()" must be either a string "all", or an array containing a subset of these values: read, create, update, delete, publish, unpublish, archive, unarchive.', $exception->getMessage());
        }

        $policy->setActions('all');

        try {
            $policy->addAction('read');
            $this->fail('Action can not be added when it is currently configured as "all".');
        } catch (\LogicException $exception) {
            $this->assertSame('Trying to add an action to a set, but the current value is a string. Use "Policy::setActions()" to initialize to an array.', $exception->getMessage());
        }

        $policy->setActions([]);

        try {
            $policy->addAction('invalid');
            $this->fail('Invalid action should throw an exception.');
        } catch (\InvalidArgumentException $exception) {
            $this->assertSame('Argument "$action" in "Policy::addAction()" must be one of these values: read, create, update, delete, publish, unpublish, archive, unarchive.', $exception->getMessage());
        }

        $policy->setActions([]);
        $policy->addAction('read');
        $this->assertSame(['read'], $policy->getActions());

        $policy->setConstraint(null);
        $this->assertNull($policy->getConstraint());
    }

    public function testJsonSerialize()
    {
        $policy = new Policy('allow', 'all', new EqualityConstraint('sys.type', 'Entry'));

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/Role/policy.json', $policy);
    }
}
