<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Management\Query;
use Contentful\Management\Resource\Entry;
use Contentful\Management\Resource\EntrySnapshot;
use Contentful\Tests\Management\BaseTestCase;

class EntrySnapshotTest extends BaseTestCase
{
    /**
     * @vcr e2e_entry_snapshot_get_one.json
     */
    public function testGetOne()
    {
        $proxy = $this->getReadOnlyEnvironmentProxy();

        $snapshot = $proxy->getEntrySnapshot('3LM5FlCdGUIM0Miqc664q6', '3omuk8H8M8wUuqHhxddXtp');
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
        $this->assertLink($this->readOnlySpaceId, 'Space', $sys->getSpace());
        $this->assertSame('2017-06-14T14:11:20.189Z', (string) $sys->getCreatedAt());
        $this->assertSame('2017-06-14T14:11:20.189Z', (string) $sys->getUpdatedAt());

        $this->assertSame([
            'space' => $this->readOnlySpaceId,
            'environment' => 'master',
            'entry' => '3LM5FlCdGUIM0Miqc664q6',
            'snapshot' => '3omuk8H8M8wUuqHhxddXtp',
        ], $snapshot->asUriParameters());
    }

    /**
     * @vcr e2e_entry_snapshot_get_one_from_space_proxy.json
     */
    public function testGetOneFromSpaceProxy()
    {
        $proxy = $this->getReadOnlySpaceProxy();

        $snapshot = $proxy->getEntrySnapshot('master', '3LM5FlCdGUIM0Miqc664q6', '3omuk8H8M8wUuqHhxddXtp');
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
        $this->assertLink($this->readOnlySpaceId, 'Space', $sys->getSpace());
        $this->assertSame('2017-06-14T14:11:20.189Z', (string) $sys->getCreatedAt());
        $this->assertSame('2017-06-14T14:11:20.189Z', (string) $sys->getUpdatedAt());

        $this->assertSame([
            'space' => $this->readOnlySpaceId,
            'environment' => 'master',
            'entry' => '3LM5FlCdGUIM0Miqc664q6',
            'snapshot' => '3omuk8H8M8wUuqHhxddXtp',
        ], $snapshot->asUriParameters());
    }

    /**
     * @vcr e2e_entry_snapshot_get_one_from_entry.json
     */
    public function testGetOneFromEntry()
    {
        $entry = $this->getReadOnlyEnvironmentProxy()->getEntry('3LM5FlCdGUIM0Miqc664q6');

        $snapshot = $entry->getSnapshot('3omuk8H8M8wUuqHhxddXtp');

        $sys = $snapshot->getSystemProperties();
        $this->assertSame('Entry', $sys->getSnapshotEntityType());
        $this->assertSame('publish', $sys->getSnapshotType());
        $this->assertLink($this->readOnlySpaceId, 'Space', $sys->getSpace());
        $this->assertSame('2017-06-14T14:11:20.189Z', (string) $sys->getCreatedAt());
        $this->assertSame('2017-06-14T14:11:20.189Z', (string) $sys->getUpdatedAt());
    }

    /**
     * @vcr e2e_entry_snapshot_get_collection.json
     */
    public function testGetCollection()
    {
        $proxy = $this->getReadOnlyEnvironmentProxy();

        $snapshots = $proxy->getEntrySnapshots('3LM5FlCdGUIM0Miqc664q6');
        $this->assertInstanceOf(EntrySnapshot::class, $snapshots[0]);

        $query = (new Query())
            ->setLimit(1)
        ;
        $snapshots = $proxy->getEntrySnapshots('3LM5FlCdGUIM0Miqc664q6', $query);
        $this->assertInstanceOf(EntrySnapshot::class, $snapshots[0]);
        $this->assertCount(1, $snapshots);
    }

    /**
     * @vcr e2e_entry_snapshot_get_collection_from_space_proxy.json
     */
    public function testGetCollectionFromSpaceProxy()
    {
        $proxy = $this->getReadOnlySpaceProxy();

        $snapshots = $proxy->getEntrySnapshots('master', '3LM5FlCdGUIM0Miqc664q6');

        $this->assertInstanceOf(EntrySnapshot::class, $snapshots[0]);

        $query = (new Query())
            ->setLimit(1)
        ;
        $snapshots = $proxy->getEntrySnapshots('master', '3LM5FlCdGUIM0Miqc664q6', $query);
        $this->assertInstanceOf(EntrySnapshot::class, $snapshots[0]);
        $this->assertCount(1, $snapshots);
    }

    /**
     * @vcr e2e_entry_snapshot_get_collection_from_entry.json
     */
    public function testGetCollectionFromEntry()
    {
        $originalEntry = $this->getReadOnlyEnvironmentProxy()->getEntry('3LM5FlCdGUIM0Miqc664q6');

        $query = (new Query())
            ->setLimit(1)
        ;
        $snapshots = $originalEntry->getSnapshots($query);

        $this->assertCount(1, $snapshots);

        $this->assertInstanceOf(EntrySnapshot::class, $snapshots[0]);
        $this->assertCount(1, $snapshots);
        $entry = $snapshots[0]->getEntry();
        $this->assertSame($originalEntry->getId(), $entry->getId());
    }
}
