<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Field;

use Contentful\Management\Field\SymbolField;
use Contentful\Management\Field\Validation\InValidation;
use Contentful\Management\Field\Validation\RangeValidation;
use Contentful\Management\Field\Validation\SizeValidation;
use PHPUnit\Framework\TestCase;

class SimpleFieldTest extends TestCase
{
    public function testGetSetData()
    {
        $field = new SymbolField('name', 'Name');

        $this->assertEquals('name', $field->getId());
        $this->assertEquals('Name', $field->getName());
        $this->assertEquals('Symbol', $field->getType());
        $this->assertCount(0, $field->getValidations());
        $this->assertFalse($field->isOmitted());
        $this->assertFalse($field->isRequired());
        $this->assertFalse($field->isDisabled());
        $this->assertFalse($field->isLocalized());

        $field->setName('Better Name');
        $this->assertEquals('Better Name', $field->getName());

        $this->assertFalse($field->isOmitted());
        $field->setOmitted(true);
        $this->assertTrue($field->isOmitted());

        $this->assertFalse($field->isRequired());
        $field->setRequired(true);
        $this->assertTrue($field->isRequired());

        $this->assertFalse($field->isDisabled());
        $field->setDisabled(true);
        $this->assertTrue($field->isDisabled());

        $this->assertFalse($field->isLocalized());
        $field->setLocalized(true);
        $this->assertTrue($field->isLocalized());

        $field->setValidations([new SizeValidation(5)]);
        $this->assertCount(1, $field->getValidations());
        $this->assertInstanceOf(SizeValidation::class, $field->getValidations()[0]);

        $field->addValidation(new InValidation(['a', 'b']));
        $this->assertCount(2, $field->getValidations());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The validation Contentful\Management\Field\Validation\RangeValidation can not be used for fields of type Symbol.
     */
    public function testAddInvalidValidation()
    {
        $field = new SymbolField('name', 'Name');

        $field->addValidation(new RangeValidation(5, 15));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The validation Contentful\Management\Field\Validation\RangeValidation can not be used for fields of type Symbol.
     */
    public function testSetInvalidValidation()
    {
        $field = new SymbolField('name', 'Name');

        $field->setValidations([new RangeValidation(5, 15)]);
    }
}
