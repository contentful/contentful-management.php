<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Link;
use Contentful\Management\ApiDateTime;
use Contentful\Management\Query;
use Contentful\Management\Resource\Entry;
use Contentful\Tests\Management\BaseTestCase;

class EntryTest extends BaseTestCase
{
    /**
     * @vcr e2e_entry_get_one.json
     */
    public function testGetEntry()
    {
        $client = $this->getDefaultClient();

        $entry = $client->entry->get('4OuC4z6qs0yEWMeqkGmokw');

        $this->assertEquals('Direwolf', $entry->getField('name', 'en-US'));
        $this->assertEquals(new Link('4OuC4z6qs0yEWMeqkGmokw', 'Entry'), $entry->asLink());

        $sys = $entry->getSystemProperties();
        $this->assertEquals('4OuC4z6qs0yEWMeqkGmokw', $sys->getId());
        $this->assertEquals('Entry', $sys->getType());
        $this->assertEquals(new Link('fantasticCreature', 'ContentType'), $sys->getContentType());
        $this->assertEquals(6, $sys->getVersion());
        $this->assertEquals(new Link($this->defaultSpaceId, 'Space'), $sys->getSpace());
        $this->assertEquals(new ApiDateTime('2017-08-22T11:50:19.841'), $sys->getCreatedAt());
        $this->assertEquals(new ApiDateTime('2017-08-22T11:50:26.991'), $sys->getUpdatedAt());
        $this->assertEquals(new Link('1CECdY5ZhqJapGieg6QS9P', 'User'), $sys->getCreatedBy());
        $this->assertEquals(new Link('1CECdY5ZhqJapGieg6QS9P', 'User'), $sys->getUpdatedBy());
        $this->assertEquals(5, $sys->getPublishedVersion());
        $this->assertEquals(1, $sys->getPublishedCounter());
        $this->assertEquals(new ApiDateTime('2017-08-22T11:50:26.990'), $sys->getPublishedAt());
        $this->assertEquals(new ApiDateTime('2017-08-22T11:50:26.990'), $sys->getFirstPublishedAt());
    }

    /**
     * @vcr e2e_entry_get_collection.json
     */
    public function testGetEntries()
    {
        $client = $this->getDefaultClient();
        $entries = $client->entry->getAll();

        $this->assertInstanceOf(Entry::class, $entries[0]);

        $query = (new Query())
            ->setLimit(1);
        $entries = $client->entry->getAll($query);
        $this->assertInstanceOf(Entry::class, $entries[0]);
        $this->assertCount(1, $entries);
    }

    /**
     * @vcr e2e_entry_create_update_publish_unpublish_archive_unarchive_delete.json
     */
    public function testCreateUpdatePublishUnpublishArchiveUnarchiveDelete()
    {
        $client = $this->getDefaultClient();

        $entry = (new Entry('testCt'))
            ->setField('name', 'en-US', 'A name');

        $client->entry->create($entry);
        $this->assertNotNull($entry->getId());
        $this->assertEquals(['name' => 'A name'], $entry->getFields('en-US'));
        $this->assertEquals(['name' => ['en-US' => 'A name']], $entry->getFields());
        $this->assertTrue($entry->getSystemProperties()->isDraft());
        $this->assertFalse($entry->getSystemProperties()->isPublished());
        $this->assertFalse($entry->getSystemProperties()->isUpdated());
        $this->assertFalse($entry->getSystemProperties()->isArchived());

        $entry->setName('en-US', 'A better name');

        $entry->update();
        $this->assertEquals('A better name', $entry->getName('en-US'));

        $entry->archive();
        $this->assertEquals(2, $entry->getSystemProperties()->getArchivedVersion());
        $this->assertInstanceOf(ApiDateTime::class, $entry->getSystemProperties()->getArchivedAt());
        $this->assertInstanceOf(Link::class, $entry->getSystemProperties()->getArchivedBy());
        $this->assertTrue($entry->getSystemProperties()->isArchived());

        $entry->unarchive();
        $this->assertNull($entry->getSystemProperties()->getArchivedVersion());
        $this->assertFalse($entry->getSystemProperties()->isArchived());

        $entry->publish();
        $this->assertEquals(4, $entry->getSystemProperties()->getPublishedVersion());
        $this->assertTrue($entry->getSystemProperties()->isPublished());

        $entry->setName('en-US', 'An even better name');
        $entry->update();
        $this->assertTrue($entry->getSystemProperties()->isPublished());
        $this->assertTrue($entry->getSystemProperties()->isUpdated());

        $entry->unpublish();
        $this->assertNull($entry->getSystemProperties()->getPublishedVersion());
        $this->assertFalse($entry->getSystemProperties()->isPublished());

        $entry->delete();
    }

    /**
     * @vcr e2e_entry_create_with_id.json
     */
    public function testCreateEntryWithGivenId()
    {
        $client = $this->getDefaultClient();

        $entry = (new Entry('testCt'))
            ->setField('name', 'en-US', 'A name');

        $client->entry->create($entry, 'myCustomTestEntry');
        $this->assertEquals('myCustomTestEntry', $entry->getId());

        $entry->delete();
    }

    /**
     * @vcr e2e_entry_create_without_fields.json
     */
    public function testCreateEntryWithoutFields()
    {
        $client = $this->getDefaultClient();

        // This entry has nothing in its `fields` property,
        // and because of this, Contentful omits the property altogether.
        // Without a default value in the ResourceBuilder, this call would cause a
        // "Undefined index: fields" error message
        $client->entry->get('2cOd0Aho3WkowMgk2C02iy');

        $this->markTestAsPassed();
    }
}
