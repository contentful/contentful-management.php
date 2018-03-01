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

        $this->assertSame('ContentType', $contentType->getSystemProperties()->getType());
        $this->assertSame('ContentType', $contentType->getType());
        $this->assertSame('Test CT', $contentType->getName());
        $this->assertSame('A cool new content type.', $contentType->getDescription());
        $this->assertCount(1, $contentType->getFields());
        $this->assertSame('name', $contentType->getFields()[0]->getId());
        $this->assertSame('name', $contentType->getDisplayField());

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
