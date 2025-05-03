<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\ContentType\Field;

use Contentful\Management\Resource\ContentType\Field\SymbolField;
use Contentful\Management\Resource\ContentType\Validation\InValidation;
use Contentful\Management\Resource\ContentType\Validation\RangeValidation;
use Contentful\Management\Resource\ContentType\Validation\SizeValidation;
use Contentful\Tests\Management\BaseTestCase;

class SimpleFieldTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $field = new SymbolField('name', 'Name');

        $this->assertSame('name', $field->getId());
        $this->assertSame('Name', $field->getName());
        $this->assertSame('Symbol', $field->getType());
        $this->assertCount(0, $field->getValidations());
        $this->assertFalse($field->isOmitted());
        $this->assertFalse($field->isRequired());
        $this->assertFalse($field->isDisabled());
        $this->assertFalse($field->isLocalized());

        $field->setName('Better Name');
        $this->assertSame('Better Name', $field->getName());

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

    public function testAddInvalidValidation()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("The validation \"Contentful\Management\Resource\ContentType\Validation\RangeValidation\" can not be used for fields of type \"Symbol\".");

        $field = new SymbolField('name', 'Name');

        $field->addValidation(new RangeValidation(5, 15));
    }

    public function testSetInvalidValidation()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("The validation \"Contentful\Management\Resource\ContentType\Validation\RangeValidation\" can not be used for fields of type \"Symbol\".");

        $field = new SymbolField('name', 'Name');

        $field->setValidations([new RangeValidation(5, 15)]);
    }
}
