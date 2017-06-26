<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\E2E;

use Contentful\Link;
use Contentful\Management\EntrySnapshot;
use Contentful\Management\Query;
use Contentful\Tests\End2EndTestCase;

class SnapshotTest extends End2EndTestCase
{
    /**
     * @vcr e2e_snapshot_entry_get.json
     */
    public function testGetEntrySnapshot()
    {
        $manager = $this->getReadWriteSpaceManager();

        $snapshot = $manager->getEntrySnapshot('3LM5FlCdGUIM0Miqc664q6', '3omuk8H8M8wUuqHhxddXtp');
        $this->assertInstanceOf(EntrySnapshot::class, $snapshot);
        $this->assertEquals('Josh Lyman', $snapshot->getField('name', 'en-US'));
        $this->assertEquals('Deputy Chief of Staff', $snapshot->getField('jobTitle', 'en-US'));
        $this->assertEquals(new Link('person', 'ContentType'), $snapshot->getEntrySystemProperties()->getContentType());
        $this->assertEquals(1, $snapshot->getEntrySystemProperties()->getPublishedCounter());

        $sys = $snapshot->getSystemProperties();
        $this->assertEquals('Entry', $sys->getSnapshotEntityType());
        $this->assertEquals('publish', $sys->getSnapshotType());
        $this->assertEquals(new Link($this->readWriteSpaceId, 'Space'), $sys->getSpace());
        $this->assertEquals(new \DateTimeImmutable('2017-06-14T14:11:20.189Z'), $sys->getCreatedAt());
        $this->assertEquals(new \DateTimeImmutable('2017-06-14T14:11:20.189Z'), $sys->getUpdatedAt());
    }

    /**
     * @vcr e2e_snapshot_entry_get_collection.json
     */
    public function testGetEntrySnapshots()
    {
        $manager = $this->getReadWriteSpaceManager();

        $snapshots = $manager->getEntrySnapshots('3LM5FlCdGUIM0Miqc664q6');

        $this->assertInstanceOf(EntrySnapshot::class, $snapshots[0]);

        $query = (new Query())
            ->setLimit(1);
        $snapshots = $manager->getEntrySnapshots('3LM5FlCdGUIM0Miqc664q6', $query);
        $this->assertInstanceOf(EntrySnapshot::class, $snapshots[0]);
        $this->assertCount(1, $snapshots);
    }
}
