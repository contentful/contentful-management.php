<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\Unit;

use Contentful\Core\Api\DateTimeImmutable;
use Contentful\Core\Api\Link;
use Contentful\Management\SystemProperties;
use Contentful\Tests\Management\BaseTestCase;

class SystemPropertiesTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $data = [
            'id' => 'entryId',
            'type' => 'Entry',
            'version' => 1,
            'revision' => 0,
            'publishedCounter' => 10,
            'publishedVersion' => 5,
            'archivedVersion' => 15,
            'snapshotType' => 'publish',
            'snapshotEntityType' => 'Entry',

            'createdAt' => '2017-01-01T12:30:15.000Z',
            'updatedAt' => '2017-02-02T12:30:15.000Z',
            'publishedAt' => '2017-03-03T12:30:15.000Z',
            'archivedAt' => '2017-04-04T12:30:15.000Z',
            'firstPublishedAt' => '2017-05-05T12:30:15.000Z',
            'expiresAt' => '2017-06-06T12:30:15.000Z',

            'space' => [
                'sys' => [
                    'type' => 'Link',
                    'linkType' => 'Space',
                    'id' => 'spaceId',
                ],
            ],
            'contentType' => [
                'sys' => [
                    'type' => 'Link',
                    'linkType' => 'ContentType',
                    'id' => 'contentTypeId',
                ],
            ],
            'createdBy' => [
                'sys' => [
                    'type' => 'Link',
                    'linkType' => 'User',
                    'id' => 'userId',
                ],
            ],
            'updatedBy' => [
                'sys' => [
                    'type' => 'Link',
                    'linkType' => 'User',
                    'id' => 'userId',
                ],
            ],
            'publishedBy' => [
                'sys' => [
                    'type' => 'Link',
                    'linkType' => 'User',
                    'id' => 'userId',
                ],
            ],
            'archivedBy' => [
                'sys' => [
                    'type' => 'Link',
                    'linkType' => 'User',
                    'id' => 'userId',
                ],
            ],
        ];

        $sys = new SystemProperties($data);

        $this->assertSame('entryId', $sys->getId());
        $this->assertSame('Entry', $sys->getType());
        $this->assertSame(1, $sys->getVersion());
        $this->assertSame(0, $sys->getRevision());
        $this->assertSame(10, $sys->getPublishedCounter());
        $this->assertSame(5, $sys->getPublishedVersion());
        $this->assertSame(15, $sys->getArchivedVersion());
        $this->assertSame('publish', $sys->getSnapshotType());
        $this->assertSame('Entry', $sys->getSnapshotEntityType());

        $this->assertInstanceOf(DateTimeImmutable::class, $sys->getCreatedAt());
        $this->assertSame('2017-01-01T12:30:15Z', (string) $sys->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $sys->getUpdatedAt());
        $this->assertSame('2017-02-02T12:30:15Z', (string) $sys->getUpdatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $sys->getPublishedAt());
        $this->assertSame('2017-03-03T12:30:15Z', (string) $sys->getPublishedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $sys->getArchivedAt());
        $this->assertSame('2017-04-04T12:30:15Z', (string) $sys->getArchivedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $sys->getFirstPublishedAt());
        $this->assertSame('2017-05-05T12:30:15Z', (string) $sys->getFirstPublishedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $sys->getExpiresAt());
        $this->assertSame('2017-06-06T12:30:15Z', (string) $sys->getExpiresAt());

        $this->assertInstanceOf(Link::class, $sys->getSpace());
        $this->assertLink('spaceId', 'Space', $sys->getSpace());
        $this->assertInstanceOf(Link::class, $sys->getContentType());
        $this->assertLink('contentTypeId', 'ContentType', $sys->getContentType());
        $this->assertInstanceOf(Link::class, $sys->getCreatedBy());
        $this->assertLink('userId', 'User', $sys->getCreatedBy());
        $this->assertInstanceOf(Link::class, $sys->getUpdatedBy());
        $this->assertLink('userId', 'User', $sys->getUpdatedBy());
        $this->assertInstanceOf(Link::class, $sys->getPublishedBy());
        $this->assertLink('userId', 'User', $sys->getPublishedBy());
        $this->assertInstanceOf(Link::class, $sys->getArchivedBy());
        $this->assertLink('userId', 'User', $sys->getArchivedBy());

        $this->assertJsonFixtureEqualsJsonObject('Unit/system_properties.json', $sys);
    }
}
