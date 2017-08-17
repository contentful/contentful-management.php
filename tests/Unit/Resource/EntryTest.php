<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Resource;

use Contentful\Link;
use Contentful\Management\Resource\Entry;
use PHPUnit\Framework\TestCase;

class EntryTest extends TestCase
{
    public function testGetSetData()
    {
        $entry = new Entry('testCt');

        $sys = $entry->getSystemProperties();
        $this->assertEquals('Entry', $sys->getType());
        $this->assertEquals(new Link('testCt', 'ContentType'), $sys->getContentType());
    }

    public function testJsonSerialize()
    {
        $entry = (new Entry('testCt'));

        $this->assertJsonStringEqualsJsonString('{"fields":{},"sys":{"type":"Entry","contentType": {"sys":{"type":"Link","id":"testCt","linkType":"ContentType"}}}}', json_encode($entry));
    }
}
