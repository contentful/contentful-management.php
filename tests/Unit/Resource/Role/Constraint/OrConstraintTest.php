<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\Role\Constraint;

use Contentful\Management\Resource\Role\Constraint\OrConstraint;
use Contentful\Tests\Management\BaseTestCase;

class OrConstraintTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $constraint = new OrConstraint();
        $child = new OrConstraint();
        $constraint->setChildren([$child]);
        $this->assertSame([$child], $constraint->getChildren());

        try {
            $constraint->setChildren(['invalid']);
            $this->fail('Invalid child should throw an exception.');
        } catch (\InvalidArgumentException $exception) {
            $this->assertSame('Argument "$children" of "Contentful\Management\Resource\Role\Constraint\OrConstraint::setChildren()" must be an array of "ConstraintInterface" objects.', $exception->getMessage());
        }

        $constraint->clearChildren();
        $this->assertSame([], $constraint->getChildren());

        $constraint->addChild($child);
        $this->assertSame([$child], $constraint->getChildren());
    }

    public function testJsonSerialize()
    {
        $constraint = new OrConstraint([new OrConstraint()]);

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/Role/Constraint/or_constraint.json', $constraint);
    }
}
