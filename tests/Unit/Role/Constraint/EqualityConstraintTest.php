<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Constraint;

use Contentful\Management\Role\Constraint\EqualityConstraint;
use PHPUnit\Framework\TestCase;

class EqualityConstraintTest extends TestCase
{
    public function testGetSetData()
    {
        $constraint = new EqualityConstraint();
        $constraint->setDoc('sys.type');
        $this->assertEquals('sys.type', $constraint->getDoc());

        $constraint->setValue('Entry');
        $this->assertEquals('Entry', $constraint->getValue());
    }

    public function testJsonSerialize()
    {
        $constraint = new EqualityConstraint('sys.type', 'Entry');

        $json = '{"equals":[{"doc":"sys.type"},"Entry"]}';
        $this->assertJsonStringEqualsJsonString($json, json_encode($constraint));
    }
}
