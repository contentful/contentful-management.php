<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Constraint;

use Contentful\Management\Role\Constraint\NotConstraint;
use PHPUnit\Framework\TestCase;

class NotConstraintTest extends TestCase
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

        $json = '{"not":[{"not":[]}]}';
        $this->assertJsonStringEqualsJsonString($json, json_encode($constraint));
    }
}
