<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\E2E;

use Contentful\Link;
use Contentful\Management\Query;
use Contentful\Management\Resource\Entry;
use Contentful\Tests\End2EndTestCase;

class EntryTest extends End2EndTestCase
{
    /**
     * @vcr e2e_entry_get.json
     */
    public function testGetEntry()
    {
        $manager = $this->getReadOnlySpaceManager();

        $entry = $manager->getEntry('nyancat');

        $this->assertEquals('Nyan Cat', $entry->getField('name', 'en-US'));
        $this->assertEquals('Nyan vIghro\'', $entry->getField('name', 'tlh'));
        $this->assertEquals(new Link('nyancat', 'Entry'), $entry->asLink());

        $sys = $entry->getSystemProperties();
        $this->assertEquals('nyancat', $sys->getId());
        $this->assertEquals('Entry', $sys->getType());
        $this->assertEquals(new Link('cat', 'ContentType'), $sys->getContentType());
        $this->assertEquals(15, $sys->getVersion());
        $this->assertEquals(new Link($this->readOnlySpaceId, 'Space'), $sys->getSpace());
        $this->assertEquals(new \DateTimeImmutable('2013-06-27T22:46:15.91'), $sys->getCreatedAt());
        $this->assertEquals(new \DateTimeImmutable('2016-05-19T11:40:57.752'), $sys->getUpdatedAt());
        $this->assertEquals(new Link('7BslKh9TdKGOK41VmLDjFZ', 'User'), $sys->getCreatedBy());
        $this->assertEquals(new Link('5NItczv8FWvPn5UTJpTOMM', 'User'), $sys->getUpdatedBy());
        $this->assertEquals(10, $sys->getPublishedVersion());
        $this->assertEquals(5, $sys->getPublishedCounter());
        $this->assertEquals(new \DateTimeImmutable('2013-09-04T09:19:39.027'), $sys->getPublishedAt());
        $this->assertEquals(new \DateTimeImmutable('2013-06-27T22:46:19.513'), $sys->getFirstPublishedAt());
    }

    /**
     * @vcr e2e_entry_get_collection.json
     */
    public function testGetEntries()
    {
        $manager = $this->getReadOnlySpaceManager();
        $entries = $manager->getEntries();

        $this->assertInstanceOf(Entry::class, $entries[0]);

        $query = (new Query())
            ->setLimit(1);
        $entries = $manager->getEntries($query);
        $this->assertInstanceOf(Entry::class, $entries[0]);
        $this->assertCount(1, $entries);
    }

    /**
     * @vcr e2e_entry_create_update_publish_unpublish_archive_unarchive_delete.json
     */
    public function testCreateUpdatePublishUnpublishArchiveUnarchiveDelete()
    {
        $manager = $this->getReadWriteSpaceManager();

        $entry = (new Entry('testCt'))
            ->setField('name', 'en-US', 'A name');

        $manager->create($entry);
        $this->assertNotNull($entry->getSystemProperties()->getId());
        $this->assertEquals(['name' => 'A name'], $entry->getFields('en-US'));
        $this->assertEquals(['name' => ['en-US' => 'A name']], $entry->getFields());

        $entry->setField('name', 'en-US', 'A better name');

        $manager->update($entry);

        $manager->archive($entry);
        $this->assertEquals(2, $entry->getSystemProperties()->getArchivedVersion());
        $this->assertInstanceOf(\DateTimeImmutable::class, $entry->getSystemProperties()->getArchivedAt());
        $this->assertInstanceOf(Link::class, $entry->getSystemProperties()->getArchivedBy());

        $manager->unarchive($entry);
        $this->assertNull($entry->getSystemProperties()->getArchivedVersion());

        $manager->publish($entry);
        $this->assertEquals(4, $entry->getSystemProperties()->getPublishedVersion());

        $manager->unpublish($entry);
        $this->assertNull($entry->getSystemProperties()->getPublishedVersion());

        $manager->delete($entry);
    }

    /**
     * @vcr e2e_entry_create_with_id.json
     */
    public function testCreateEntryWithGivenId()
    {
        $manager = $this->getReadWriteSpaceManager();

        $entry = (new Entry('testCt'))
            ->setField('name', 'en-US', 'A name');

        $manager->create($entry, 'myCustomTestEntry');
        $this->assertEquals('myCustomTestEntry', $entry->getSystemProperties()->getId());

        $manager->delete($entry);
    }

    /**
     * @vcr e2e_entry_create_without_fields.json
     */
    public function testCreateEntryWithoutFields()
    {
        $manager = $this->getReadWriteSpaceManager();

        // This entry has nothing in its `fields` property,
        // and because of this, Contentful omits the property altogether.
        // Without a default value in the ResourceBuilder, this call would cause a
        // "Undefined index: fields" error message
        $manager->getEntry('2cOd0Aho3WkowMgk2C02iy');

        $this->markTestAsPassed();
    }
}
