<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Resource;

use Contentful\Management\Field\NumberField;
use Contentful\Management\Field\SymbolField;
use Contentful\Management\Resource\ContentType;
use PHPUnit\Framework\TestCase;

class ContentTypeTest extends TestCase
{
    public function testGetSetData()
    {
        $contentType = (new ContentType('Test CT'))
            ->setDescription('A cool new content type.')
            ->setFields([
                new SymbolField('name', 'Name'),
            ])
            ->setDisplayField('name');

        $this->assertEquals('ContentType', $contentType->getSystemProperties()->getType());
        $this->assertEquals('Test CT', $contentType->getName());
        $this->assertEquals('A cool new content type.', $contentType->getDescription());
        $this->assertCount(1, $contentType->getFields());
        $this->assertEquals('name', $contentType->getFields()[0]->getId());
        $this->assertEquals('name', $contentType->getDisplayField());

        $contentType->addField(new NumberField('number', 'Number'));
        $this->assertCount(2, $contentType->getFields());
    }

    public function testJsonSerialize()
    {
        $contentType = (new ContentType('Test CT'))
            ->setDescription('A cool new content type.')
            ->setFields([
                new SymbolField('name', 'Name'),
                new NumberField('number', 'Number'),
            ])
            ->setDisplayField('name');

        $this->assertJsonStringEqualsJsonString('{"sys": {"type": "ContentType"}, "name": "Test CT", "fields": [{"id":"name","name":"Name","type":"Symbol","required": false,"localized": false,"disabled": false,"omitted": false}, {"id":"number","name":"Number","type":"Number","required": false,"localized": false,"disabled": false,"omitted": false}], "description": "A cool new content type.", "displayField": "name"}', json_encode($contentType));
    }
}
