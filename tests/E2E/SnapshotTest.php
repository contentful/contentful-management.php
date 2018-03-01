<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Management\Query;
use Contentful\Management\Resource\ContentType;
use Contentful\Management\Resource\ContentTypeSnapshot;
use Contentful\Management\Resource\Entry;
use Contentful\Management\Resource\EntrySnapshot;
use Contentful\Tests\Management\BaseTestCase;

class SnapshotTest extends BaseTestCase
{
    /**
     * @vcr e2e_snapshot_entry_get_one.json
     */
    public function testGetEntrySnapshot()
    {
        $client = $this->getDefaultClient();

        $snapshot = $client->entrySnapshot->get('3LM5FlCdGUIM0Miqc664q6', '3omuk8H8M8wUuqHhxddXtp');
        $this->assertLink('3omuk8H8M8wUuqHhxddXtp', 'Snapshot', $snapshot->asLink());
        $this->assertSame($snapshot->getEntry(), $snapshot->getSnapshot());
        $entry = $snapshot->getEntry();
        $this->assertInstanceOf(Entry::class, $entry);
        $this->assertSame('Josh Lyman', $entry->getField('name', 'en-US'));
        $this->assertSame('Deputy Chief of Staff', $entry->getField('jobTitle', 'en-US'));
        $this->assertLink('person', 'ContentType', $entry->getSystemProperties()->getContentType());
        $this->assertSame(1, $entry->getSystemProperties()->getPublishedCounter());

        $sys = $snapshot->getSystemProperties();
        $this->assertSame('Entry', $sys->getSnapshotEntityType());
        $this->assertSame('publish', $sys->getSnapshotType());
        $this->assertLink($this->defaultSpaceId, 'Space', $sys->getSpace());
        $this->assertSame('2017-06-14T14:11:20.189Z', (string) $sys->getCreatedAt());
        $this->assertSame('2017-06-14T14:11:20.189Z', (string) $sys->getUpdatedAt());
    }

    /**
     * @vcr e2e_snapshot_entry_get_collection.json
     */
    public function testGetEntrySnapshots()
    {
        $client = $this->getDefaultClient();

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
        $client = $this->getDefaultClient();

        $snapshot = $client->contentTypeSnapshot->get('versionedContentType', '1Qvx64r3Nq0MOtftdcAXJO');

        $this->assertLink('1Qvx64r3Nq0MOtftdcAXJO', 'Snapshot', $snapshot->asLink());
        $this->assertSame($snapshot->getContentType(), $snapshot->getSnapshot());
        $contentType = $snapshot->getContentType();
        $this->assertInstanceOf(ContentType::class, $contentType);
        $this->assertSame('Versioned Content Type', $contentType->getName());
        $this->assertSame('title', $contentType->getDisplayField());
        $this->assertSame(2, $contentType->getSystemProperties()->getVersion());
        $this->assertSame(1, $contentType->getSystemProperties()->getPublishedCounter());

        $sys = $snapshot->getSystemProperties();
        $this->assertSame('ContentType', $sys->getSnapshotEntityType());
        $this->assertSame('publish', $sys->getSnapshotType());
        $this->assertLink($this->defaultSpaceId, 'Space', $sys->getSpace());
        $this->assertSame('2017-07-05T16:46:58.486Z', (string) $sys->getCreatedAt());
        $this->assertSame('2017-07-05T16:46:58.486Z', (string) $sys->getUpdatedAt());
    }

    /**
     * @vcr e2e_snapshot_content_type_get_collection.json
     */
    public function testGetContentTypeSnapshots()
    {
        $client = $this->getDefaultClient();

        $snapshots = $client->contentTypeSnapshot->getAll('versionedContentType');

        $snapshot = $snapshots[0];
        $contentType = $snapshot->getContentType();
        $fields = $contentType->getFields();
        $this->assertInstanceOf(ContentTypeSnapshot::class, $snapshot);
        $this->assertSame('S9fDVyecKeNclEq1BPwuD', $snapshot->getId());
        $this->assertSame(2, \count($fields));
        $this->assertSame('Title', $fields[0]->getName());
        $this->assertSame('Description', $fields[1]->getName());
        $this->assertSame(2, $contentType->getSystemProperties()->getPublishedCounter());

        $snapshot = $snapshots[1];
        $contentType = $snapshot->getContentType();
        $fields = $contentType->getFields();
        $this->assertInstanceOf(ContentTypeSnapshot::class, $snapshot);
        $this->assertSame('1Qvx64r3Nq0MOtftdcAXJO', $snapshot->getId());
        $this->assertSame(1, \count($fields));
        $this->assertSame('Title', $fields[0]->getName());
        $this->assertSame(1, $contentType->getSystemProperties()->getPublishedCounter());

        $query = (new Query())
            ->setLimit(1);
        $snapshots = $client->contentTypeSnapshot->getAll('versionedContentType', $query);
        $this->assertCount(1, $snapshots);

        $snapshot = $snapshots[0];
        $contentType = $snapshot->getContentType();
        $fields = $contentType->getFields();
        $this->assertInstanceOf(ContentTypeSnapshot::class, $snapshot);
        $this->assertSame('S9fDVyecKeNclEq1BPwuD', $snapshot->getId());
        $this->assertSame(2, \count($fields));
        $this->assertSame('Title', $fields[0]->getName());
        $this->assertSame('Description', $fields[1]->getName());
        $this->assertSame(2, $contentType->getSystemProperties()->getPublishedCounter());
    }
}
