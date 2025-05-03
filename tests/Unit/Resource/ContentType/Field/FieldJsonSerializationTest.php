<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\ContentType\Field;

use Contentful\Management\Resource\ContentType\Field;
use Contentful\Management\Resource\ContentType\Field\FieldInterface;
use Contentful\Tests\Management\BaseTestCase;

class FieldJsonSerializationTest extends BaseTestCase
{
    /**
     * @dataProvider fieldProvider
     *
     * @param string         $fixture
     * @param FieldInterface $field
     */
    public function testJsonSerialization($fixture, $field)
    {
        $this->assertJsonFixtureEqualsJsonObject($fixture, $field);
    }

    public function fieldProvider()
    {
        return [
            'array field' => [
                'Unit/Resource/ContentType/Field/array_field.json',
                new Field\ArrayField('someId', 'A name', 'Symbol'),
            ],
            'boolean field' => [
                'Unit/Resource/ContentType/Field/boolean_field.json',
                new Field\BooleanField('someId', 'A name'),
            ],
            'date field' => [
                'Unit/Resource/ContentType/Field/date_field.json',
                new Field\DateField('someId', 'A name'),
            ],
            'integer field' => [
                'Unit/Resource/ContentType/Field/integer_field.json',
                new Field\IntegerField('someId', 'A name'),
            ],
            'link field' => [
                'Unit/Resource/ContentType/Field/link_field.json',
                new Field\LinkField('someId', 'A name', 'Entry'),
            ],
            'location field' => [
                'Unit/Resource/ContentType/Field/location_field.json',
                new Field\LocationField('someId', 'A name'),
            ],
            'number field' => [
                'Unit/Resource/ContentType/Field/number_field.json',
                new Field\NumberField('someId', 'A name'),
            ],
            'object field' => [
                'Unit/Resource/ContentType/Field/object_field.json',
                new Field\ObjectField('someId', 'A name'),
            ],
            'rich text field' => [
                'Unit/Resource/ContentType/Field/rich_text_field.json',
                new Field\RichTextField('someId', 'A name'),
            ],
            'symbol field' => [
                'Unit/Resource/ContentType/Field/symbol_field.json',
                new Field\SymbolField('someId', 'A name'),
            ],
            'text field' => [
                'Unit/Resource/ContentType/Field/text_field.json',
                new Field\TextField('someId', 'A name'),
            ],
        ];
    }
}
