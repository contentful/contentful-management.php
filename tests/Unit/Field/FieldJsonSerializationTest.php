<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Field;

use Contentful\Management\Field;
use PHPUnit\Framework\TestCase;

class FieldJsonSerializationTest extends TestCase
{
    /**
     * @dataProvider fieldProvider
     */
    public function testJsonSerialization($field, $expected)
    {
        $this->assertEquals($expected, json_encode($field));
    }

    public function fieldProvider()
    {
        return [
            'array field' => [
                new Field\ArrayField('someId', 'A name', 'Symbol'),
                '{"name":"A name","id":"someId","type":"Array","required":false,"localized":false,"disabled":false,"omitted":false,"items":{"type":"Symbol"}}',
            ],
            'boolean field' => [
                new Field\BooleanField('someId', 'A name'),
                '{"name":"A name","id":"someId","type":"Boolean","required":false,"localized":false,"disabled":false,"omitted":false}',
            ],
            'date field' => [
                new Field\DateField('someId', 'A name'),
                '{"name":"A name","id":"someId","type":"Date","required":false,"localized":false,"disabled":false,"omitted":false}',
            ],
            'integer field' => [
                new Field\IntegerField('someId', 'A name'),
                '{"name":"A name","id":"someId","type":"Integer","required":false,"localized":false,"disabled":false,"omitted":false}',
            ],
            'link field' => [
                new Field\LinkField('someId', 'A name', 'Entry'),
                '{"name":"A name","id":"someId","type":"Link","required":false,"localized":false,"disabled":false,"omitted":false,"linkType":"Entry"}',
            ],
            'location field' => [
                new Field\LocationField('someId', 'A name'),
                '{"name":"A name","id":"someId","type":"Location","required":false,"localized":false,"disabled":false,"omitted":false}',
            ],
            'number field' => [
                new Field\NumberField('someId', 'A name'),
                '{"name":"A name","id":"someId","type":"Number","required":false,"localized":false,"disabled":false,"omitted":false}',
            ],
            'object field' => [
                new Field\ObjectField('someId', 'A name'),
                '{"name":"A name","id":"someId","type":"Object","required":false,"localized":false,"disabled":false,"omitted":false}',
            ],
            'symbol field' => [
                new Field\SymbolField('someId', 'A name'),
                '{"name":"A name","id":"someId","type":"Symbol","required":false,"localized":false,"disabled":false,"omitted":false}',
            ],
            'text field' => [
                new Field\TextField('someId', 'A name'),
                '{"name":"A name","id":"someId","type":"Text","required":false,"localized":false,"disabled":false,"omitted":false}',
            ],
        ];
    }
}
