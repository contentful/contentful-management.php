<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource;

use Contentful\Link;
use Contentful\Management\ApiDateTime;
use Contentful\Management\Resource\Entry;
use Contentful\Tests\Management\BaseTestCase;

class EntryTest extends BaseTestCase
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

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/entry.json', $entry);
    }
}
