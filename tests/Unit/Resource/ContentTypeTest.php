<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource;

use Contentful\Management\Resource\ContentType;
use Contentful\Management\Resource\ContentType\Field\NumberField;
use Contentful\Management\Resource\ContentType\Field\SymbolField;
use Contentful\Tests\Management\BaseTestCase;

class ContentTypeTest extends BaseTestCase
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

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/content_type.json', $contentType);
    }
}
