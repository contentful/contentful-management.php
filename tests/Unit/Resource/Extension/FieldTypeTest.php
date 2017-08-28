<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Management\Resource\Extension;

use Contentful\Management\Resource\Extension\FieldType;
use PHPUnit\Framework\TestCase;

class FieldTypeTest extends TestCase
{
    public function testGetSetData()
    {
        $this->assertEquals(['type' => 'Symbol'], (new FieldType('Symbol'))->getData());
        $this->assertEquals(['type' => 'Text'], (new FieldType('Text'))->getData());
        $this->assertEquals(['type' => 'Integer'], (new FieldType('Integer'))->getData());
        $this->assertEquals(['type' => 'Number'], (new FieldType('Number'))->getData());
        $this->assertEquals(['type' => 'Date'], (new FieldType('Date'))->getData());
        $this->assertEquals(['type' => 'Boolean'], (new FieldType('Boolean'))->getData());
        $this->assertEquals(['type' => 'Object'], (new FieldType('Object'))->getData());
        $this->assertEquals(['type' => 'Symbol'], (new FieldType('Symbol'))->getData());
        $this->assertEquals(['type' => 'Link', 'linkType' => 'Asset'], (new FieldType('Link', ['Asset']))->getData());
        $this->assertEquals(['type' => 'Link', 'linkType' => 'Entry'], (new FieldType('Link', ['Entry']))->getData());
        $this->assertEquals(['type' => 'Array', 'items' => ['type' => 'Symbol']], (new FieldType('Array', ['Symbol']))->getData());
        $this->assertEquals(['type' => 'Array', 'items' => ['type' => 'Link', 'linkType' => 'Asset']], (new FieldType('Array', ['Link', 'Asset']))->getData());
        $this->assertEquals(['type' => 'Array', 'items' => ['type' => 'Link', 'linkType' => 'Entry']], (new FieldType('Array', ['Link', 'Entry']))->getData());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Trying to create invalid extension field type "invalidType".
     */
    public function testInvalidType()
    {
        new FieldType('invalidType');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Trying to create link field type, but link type must be either "Entry" or "Asset", "invalidLinkType" given.
     */
    public function testInvalidLinkType()
    {
        new FieldType('Link', ['invalidLinkType']);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Trying to create array field type using invalid type "invalidArrayType".
     */
    public function testInvalidArrayType()
    {
        new FieldType('Array', ['invalidArrayType']);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Trying to create array field type with items type "Link", but link type must be either "Entry" or "Asset", "invalidArrayLinkType" given.
     */
    public function testInvalidArrayLinkType()
    {
        new FieldType('Array', ['Link', 'invalidArrayLinkType']);
    }
}
