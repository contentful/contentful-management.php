<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Constraint;

use Contentful\Management\Role\Constraint\OrConstraint;
use PHPUnit\Framework\TestCase;

class OrConstraintTest extends TestCase
{
    public function testGetSetData()
    {
        $constraint = new OrConstraint();
        $child = new OrConstraint();
        $constraint->setChildren([$child]);
        $this->assertSame([$child], $constraint->getChildren());

        try {
            $constraint->setChildren(['invalid']);
            $this->fail('Invalid child should throw an exception');
        } catch (\InvalidArgumentException $e) {
        }

        $constraint->clearChildren();
        $this->assertEquals([], $constraint->getChildren());

        $constraint->addChild($child);
        $this->assertSame([$child], $constraint->getChildren());
    }

    public function testJsonSerialize()
    {
        $constraint = new OrConstraint([new OrConstraint()]);

        $json = '{"or":[{"or":[]}]}';
        $this->assertJsonStringEqualsJsonString($json, json_encode($constraint));
    }
}
