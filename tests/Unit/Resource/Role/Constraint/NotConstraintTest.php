<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\Role\Constraint;

use Contentful\Management\Resource\Role\Constraint\NotConstraint;
use Contentful\Tests\Management\BaseTestCase;

class NotConstraintTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $constraint = new NotConstraint();
        $child = new NotConstraint();
        $constraint->setChild($child);
        $this->assertSame($child, $constraint->getChild());
    }

    public function testJsonSerialize()
    {
        $constraint = new NotConstraint(new NotConstraint());

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/Role/Constraint/not_constraint.json', $constraint);
    }
}
