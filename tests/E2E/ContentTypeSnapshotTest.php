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
use Contentful\Tests\Management\BaseTestCase;

class ContentTypeSnapshotTest extends BaseTestCase
{
    /**
     * @vcr e2e_content_type_snapshot_get_one.json
     */
    public function testGetOne()
    {
        $proxy = $this->getDefaultEnvironmentProxy();

        $snapshot = $proxy->getContentTypeSnapshot('versionedContentType', '1Qvx64r3Nq0MOtftdcAXJO');

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

        $this->assertSame([
            'space' => $this->defaultSpaceId,
            'environment' => 'master',
            'contentType' => 'versionedContentType',
            'snapshot' => '1Qvx64r3Nq0MOtftdcAXJO',
        ], $snapshot->asUriParameters());
    }

    /**
     * @vcr e2e_content_type_snapshot_get_one_from_content_type.json
     */
    public function testGetOneFromContentType()
    {
        $contentType = $this->getDefaultEnvironmentProxy()->getContentType('versionedContentType');

        $snapshot = $contentType->getSnapshot('1Qvx64r3Nq0MOtftdcAXJO');

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

        $this->assertSame([
            'space' => $this->defaultSpaceId,
            'environment' => 'master',
            'contentType' => 'versionedContentType',
            'snapshot' => '1Qvx64r3Nq0MOtftdcAXJO',
        ], $snapshot->asUriParameters());
    }

    /**
     * @vcr e2e_content_type_snapshot_get_from_space_proxy.json
     */
    public function testGetOneFromSpaceProxy()
    {
        $proxy = $this->getDefaultSpaceProxy();

        $snapshot = $proxy->getContentTypeSnapshot('master', 'versionedContentType', '1Qvx64r3Nq0MOtftdcAXJO');

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

        $this->assertSame([
            'space' => $this->defaultSpaceId,
            'environment' => 'master',
            'contentType' => 'versionedContentType',
            'snapshot' => '1Qvx64r3Nq0MOtftdcAXJO',
        ], $snapshot->asUriParameters());
    }

    /**
     * @vcr e2e_content_type_snapshot_get_collection.json
     */
    public function testGetCollection()
    {
        $proxy = $this->getDefaultEnvironmentProxy();

        $snapshots = $proxy->getContentTypeSnapshots('versionedContentType');

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
        $snapshots = $proxy->getContentTypeSnapshots('versionedContentType', $query);
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

    /**
     * @vcr e2e_content_type_snapshot_get_collection_from_content_type.json
     */
    public function testGetCollectionFromContentType()
    {
        $originalContentType = $this->getDefaultEnvironmentProxy()->getContentType('versionedContentType');

        $snapshots = $originalContentType->getSnapshots();

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
        $snapshots = $originalContentType->getSnapshots($query);
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

    /**
     * @vcr e2e_content_type_snapshot_get_all_from_space_proxy.json
     */
    public function testGetCollectionFromSpaceProxy()
    {
        $proxy = $this->getDefaultSpaceProxy();

        $snapshots = $proxy->getContentTypeSnapshots('master', 'versionedContentType');

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
        $snapshots = $proxy->getContentTypeSnapshots('master', 'versionedContentType', $query);
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
