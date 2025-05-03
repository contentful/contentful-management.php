<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\Role\Constraint;

use Contentful\Management\Resource\Role\Constraint\PathsConstraint;
use Contentful\Tests\Management\BaseTestCase;

class PathsConstraintTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $constraint = new PathsConstraint();
        $constraint->setDoc('fields.%.%');
        $this->assertSame('fields.%.%', $constraint->getDoc());
    }

    public function testJsonSerialize()
    {
        $constraint = new PathsConstraint('fields.%.%');

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/Role/Constraint/paths_constraint.json', $constraint);
    }
}
