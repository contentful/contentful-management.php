<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\Extension;

use Contentful\Management\Resource\Extension\FieldType;
use Contentful\Tests\Management\BaseTestCase;

class FieldTypeTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $this->assertSame(['type' => 'Symbol'], (new FieldType('Symbol'))->getData());
        $this->assertSame(['type' => 'Text'], (new FieldType('Text'))->getData());
        $this->assertSame(['type' => 'Integer'], (new FieldType('Integer'))->getData());
        $this->assertSame(['type' => 'Number'], (new FieldType('Number'))->getData());
        $this->assertSame(['type' => 'Date'], (new FieldType('Date'))->getData());
        $this->assertSame(['type' => 'Boolean'], (new FieldType('Boolean'))->getData());
        $this->assertSame(['type' => 'Object'], (new FieldType('Object'))->getData());
        $this->assertSame(['type' => 'Symbol'], (new FieldType('Symbol'))->getData());
        $this->assertSame(['type' => 'Link', 'linkType' => 'Asset'], (new FieldType('Link', ['Asset']))->getData());
        $this->assertSame(['type' => 'Link', 'linkType' => 'Entry'], (new FieldType('Link', ['Entry']))->getData());
        $this->assertSame(['type' => 'Array', 'items' => ['type' => 'Symbol']], (new FieldType('Array', ['Symbol']))->getData());
        $this->assertSame(['type' => 'Array', 'items' => ['type' => 'Link', 'linkType' => 'Asset']], (new FieldType('Array', ['Link', 'Asset']))->getData());
        $this->assertSame(['type' => 'Array', 'items' => ['type' => 'Link', 'linkType' => 'Entry']], (new FieldType('Array', ['Link', 'Entry']))->getData());
    }

    public function testInvalidType()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Trying to create invalid extension field type "invalidType".');

        new FieldType('invalidType');
    }

    public function testInvalidLinkType()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Trying to create link field type, but link type must be either "Entry" or "Asset", "invalidLinkType" given.');

        new FieldType('Link', ['invalidLinkType']);
    }

    public function testInvalidArrayType()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Trying to create array field type using invalid type "invalidArrayType".');

        new FieldType('Array', ['invalidArrayType']);
    }

    public function testInvalidArrayLinkType()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Trying to create array field type with items type "Link", but link type must be either "Entry" or "Asset", "invalidArrayLinkType" given.');

        new FieldType('Array', ['Link', 'invalidArrayLinkType']);
    }
}
