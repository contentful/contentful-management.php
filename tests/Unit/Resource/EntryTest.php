<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Management\Resource;

use Contentful\Link;
use Contentful\Management\ApiDateTime;
use Contentful\Management\Resource\Entry;
use PHPUnit\Framework\TestCase;

class EntryTest extends TestCase
{
    public function testGetSetData()
    {
        $entry = new Entry('blogPost');

        $sys = $entry->getSystemProperties();
        $this->assertEquals('Entry', $sys->getType());
        $this->assertEquals(new Link('blogPost', 'ContentType'), $sys->getContentType());
    }

    public function testJsonSerialize()
    {
        $entry = (new Entry('blogPost'))
            ->setField('title', 'en-US', 'My summer holidays')
            ->setField('publishedAt', 'en-US', new ApiDateTime('2017-01-01 16:30:00'))
            ->setField('tags', 'en-US', ['italy', 'venice', 'rome', 'sicily']);

        $json = '{"fields":{"title":{"en-US":"My summer holidays"},"publishedAt":{"en-US":"2017-01-01T16:30:00Z"},"tags":{"en-US":["italy","venice","rome","sicily"]}

        },"sys":{"type":"Entry","contentType": {"sys":{"type":"Link","id":"blogPost","linkType":"ContentType"}}}}';

        $this->assertJsonStringEqualsJsonString($json, json_encode($entry));
    }
}
