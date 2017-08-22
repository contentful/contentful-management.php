<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit;

use Contentful\Management\Role\Constraint\EqualityConstraint;
use Contentful\Management\Role\Policy;
use PHPUnit\Framework\TestCase;

class PolicyTest extends TestCase
{
    public function testGetSetData()
    {
        $policy = new Policy('allow');
        // These are the possible values
        $policy->setEffect('deny');
        $this->assertEquals('deny', $policy->getEffect());

        try {
            $policy->setEffect('invalid');
            $this->fail('Invalid effect should throw an exception');
        } catch (\InvalidArgumentException $e) {
        }

        try {
            $policy->setActions('invalid');
            $this->fail('Invalid actions should throw an exception');
        } catch (\InvalidArgumentException $e) {
        }

        $policy->setActions('all');

        try {
            $policy->addAction('read');
            $this->fail('Action can not be added when it is currently configured as "all"');
        } catch (\LogicException $e) {
        }

        $policy->setActions([]);

        try {
            $policy->addAction('invalid');
            $this->fail('Invalid action should throw an exception');
        } catch (\InvalidArgumentException $e) {
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

        $json = '{"effect":"allow","actions":"all","constraint":{"equals":[{"doc":"sys.type"}, "Entry"]}}';
        $this->assertJsonStringEqualsJsonString($json, json_encode($policy));
    }
}
