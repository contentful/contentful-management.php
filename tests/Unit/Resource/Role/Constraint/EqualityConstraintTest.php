<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\Role\Constraint;

use Contentful\Management\Resource\Role\Constraint\EqualityConstraint;
use Contentful\Tests\Management\BaseTestCase;

class EqualityConstraintTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $constraint = new EqualityConstraint();
        $constraint->setDoc('sys.type');
        $this->assertSame('sys.type', $constraint->getDoc());

        $constraint->setValue('Entry');
        $this->assertSame('Entry', $constraint->getValue());
    }

    public function testJsonSerialize()
    {
        $constraint = new EqualityConstraint('sys.type', 'Entry');

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/Role/Constraint/equality_constraint.json', $constraint);
    }
}
