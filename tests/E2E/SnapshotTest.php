<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\E2E\Management;

use Contentful\Link;
use Contentful\Management\ApiDateTime;
use Contentful\Management\Query;
use Contentful\Management\Resource\ContentType;
use Contentful\Management\Resource\ContentTypeSnapshot;
use Contentful\Management\Resource\Entry;
use Contentful\Management\Resource\EntrySnapshot;
use Contentful\Tests\End2EndTestCase;

class SnapshotTest extends End2EndTestCase
{
    /**
     * @vcr e2e_snapshot_entry_get_one.json
     */
    public function testGetEntrySnapshot()
    {
        $client = $this->getReadWriteClient();

        $snapshot = $client->entrySnapshot->get('3LM5FlCdGUIM0Miqc664q6', '3omuk8H8M8wUuqHhxddXtp');
        $this->assertEquals(new Link('3omuk8H8M8wUuqHhxddXtp', 'Snapshot'), $snapshot->asLink());
        $this->assertSame($snapshot->getEntry(), $snapshot->getSnapshot());
        $entry = $snapshot->getEntry();
        $this->assertInstanceOf(Entry::class, $entry);
        $this->assertEquals('Josh Lyman', $entry->getField('name', 'en-US'));
        $this->assertEquals('Deputy Chief of Staff', $entry->getField('jobTitle', 'en-US'));
        $this->assertEquals(new Link('person', 'ContentType'), $entry->getSystemProperties()->getContentType());
        $this->assertEquals(1, $entry->getSystemProperties()->getPublishedCounter());

        $sys = $snapshot->getSystemProperties();
        $this->assertEquals('Entry', $sys->getSnapshotEntityType());
        $this->assertEquals('publish', $sys->getSnapshotType());
        $this->assertEquals(new Link($this->readWriteSpaceId, 'Space'), $sys->getSpace());
        $this->assertEquals(new ApiDateTime('2017-06-14T14:11:20.189Z'), $sys->getCreatedAt());
        $this->assertEquals(new ApiDateTime('2017-06-14T14:11:20.189Z'), $sys->getUpdatedAt());
    }

    /**
     * @vcr e2e_snapshot_entry_get_collection.json
     */
    public function testGetEntrySnapshots()
    {
        $client = $this->getReadWriteClient();

        $snapshots = $client->entrySnapshot->getAll('3LM5FlCdGUIM0Miqc664q6');

        $this->assertInstanceOf(EntrySnapshot::class, $snapshots[0]);

        $query = (new Query())
            ->setLimit(1);
        $snapshots = $client->entrySnapshot->getAll('3LM5FlCdGUIM0Miqc664q6', $query);
        $this->assertInstanceOf(EntrySnapshot::class, $snapshots[0]);
        $this->assertCount(1, $snapshots);
    }

    /**
     * @vcr e2e_snapshot_content_type_get_one.json
     */
    public function testGetContentTypeSnapshot()
    {
        $client = $this->getReadWriteClient();

        $snapshot = $client->contentTypeSnapshot->get('versionedContentType', '1Qvx64r3Nq0MOtftdcAXJO');

        $this->assertEquals(new Link('1Qvx64r3Nq0MOtftdcAXJO', 'Snapshot'), $snapshot->asLink());
        $this->assertSame($snapshot->getContentType(), $snapshot->getSnapshot());
        $contentType = $snapshot->getContentType();
        $this->assertInstanceOf(ContentType::class, $contentType);
        $this->assertEquals('Versioned Content Type', $contentType->getName());
        $this->assertEquals('title', $contentType->getDisplayField());
        $this->assertEquals(2, $contentType->getSystemProperties()->getVersion());
        $this->assertEquals(1, $contentType->getSystemProperties()->getPublishedCounter());

        $sys = $snapshot->getSystemProperties();
        $this->assertEquals('ContentType', $sys->getSnapshotEntityType());
        $this->assertEquals('publish', $sys->getSnapshotType());
        $this->assertEquals(new Link($this->readWriteSpaceId, 'Space'), $sys->getSpace());
        $this->assertEquals(new ApiDateTime('2017-07-05T16:46:58.486Z'), $sys->getCreatedAt());
        $this->assertEquals(new ApiDateTime('2017-07-05T16:46:58.486Z'), $sys->getUpdatedAt());
    }

    /**
     * @vcr e2e_snapshot_content_type_get_collection.json
     */
    public function testGetContentTypeSnapshots()
    {
        $client = $this->getReadWriteClient();

        $snapshots = $client->contentTypeSnapshot->getAll('versionedContentType');

        $snapshot = $snapshots[0];
        $contentType = $snapshot->getContentType();
        $fields = $contentType->getFields();
        $this->assertInstanceOf(ContentTypeSnapshot::class, $snapshot);
        $this->assertEquals('S9fDVyecKeNclEq1BPwuD', $snapshot->getId());
        $this->assertEquals(2, count($fields));
        $this->assertEquals('Title', $fields[0]->getName());
        $this->assertEquals('Description', $fields[1]->getName());
        $this->assertEquals(2, $contentType->getSystemProperties()->getPublishedCounter());

        $snapshot = $snapshots[1];
        $contentType = $snapshot->getContentType();
        $fields = $contentType->getFields();
        $this->assertInstanceOf(ContentTypeSnapshot::class, $snapshot);
        $this->assertEquals('1Qvx64r3Nq0MOtftdcAXJO', $snapshot->getId());
        $this->assertEquals(1, count($fields));
        $this->assertEquals('Title', $fields[0]->getName());
        $this->assertEquals(1, $contentType->getSystemProperties()->getPublishedCounter());

        $query = (new Query())
            ->setLimit(1);
        $snapshots = $client->contentTypeSnapshot->getAll('versionedContentType', $query);
        $this->assertCount(1, $snapshots);

        $snapshot = $snapshots[0];
        $contentType = $snapshot->getContentType();
        $fields = $contentType->getFields();
        $this->assertInstanceOf(ContentTypeSnapshot::class, $snapshot);
        $this->assertEquals('S9fDVyecKeNclEq1BPwuD', $snapshot->getId());
        $this->assertEquals(2, count($fields));
        $this->assertEquals('Title', $fields[0]->getName());
        $this->assertEquals('Description', $fields[1]->getName());
        $this->assertEquals(2, $contentType->getSystemProperties()->getPublishedCounter());
    }
}
