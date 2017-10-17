<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\Unit;

use Contentful\Link;
use Contentful\Management\ApiDateTime;
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

        $this->assertEquals('entryId', $sys->getId());
        $this->assertEquals('Entry', $sys->getType());
        $this->assertEquals(1, $sys->getVersion());
        $this->assertEquals(0, $sys->getRevision());
        $this->assertEquals(10, $sys->getPublishedCounter());
        $this->assertEquals(5, $sys->getPublishedVersion());
        $this->assertEquals(15, $sys->getArchivedVersion());
        $this->assertEquals('publish', $sys->getSnapshotType());
        $this->assertEquals('Entry', $sys->getSnapshotEntityType());

        $this->assertInstanceOf(ApiDateTime::class, $sys->getCreatedAt());
        $this->assertEquals(new ApiDateTime('2017-01-01T12:30:15.000Z'), $sys->getCreatedAt());
        $this->assertInstanceOf(ApiDateTime::class, $sys->getUpdatedAt());
        $this->assertEquals(new ApiDateTime('2017-02-02T12:30:15.000Z'), $sys->getUpdatedAt());
        $this->assertInstanceOf(ApiDateTime::class, $sys->getPublishedAt());
        $this->assertEquals(new ApiDateTime('2017-03-03T12:30:15.000Z'), $sys->getPublishedAt());
        $this->assertInstanceOf(ApiDateTime::class, $sys->getArchivedAt());
        $this->assertEquals(new ApiDateTime('2017-04-04T12:30:15.000Z'), $sys->getArchivedAt());
        $this->assertInstanceOf(ApiDateTime::class, $sys->getFirstPublishedAt());
        $this->assertEquals(new ApiDateTime('2017-05-05T12:30:15.000Z'), $sys->getFirstPublishedAt());
        $this->assertInstanceOf(ApiDateTime::class, $sys->getExpiresAt());
        $this->assertEquals(new ApiDateTime('2017-06-06T12:30:15.000Z'), $sys->getExpiresAt());

        $this->assertInstanceOf(Link::class, $sys->getSpace());
        $this->assertEquals('Space', $sys->getSpace()->getLinkType());
        $this->assertEquals('spaceId', $sys->getSpace()->getId());
        $this->assertInstanceOf(Link::class, $sys->getContentType());
        $this->assertEquals('ContentType', $sys->getContentType()->getLinkType());
        $this->assertEquals('contentTypeId', $sys->getContentType()->getId());
        $this->assertInstanceOf(Link::class, $sys->getCreatedBy());
        $this->assertEquals('User', $sys->getCreatedBy()->getLinkType());
        $this->assertEquals('userId', $sys->getCreatedBy()->getId());
        $this->assertInstanceOf(Link::class, $sys->getUpdatedBy());
        $this->assertEquals('User', $sys->getUpdatedBy()->getLinkType());
        $this->assertEquals('userId', $sys->getUpdatedBy()->getId());
        $this->assertInstanceOf(Link::class, $sys->getPublishedBy());
        $this->assertEquals('User', $sys->getPublishedBy()->getLinkType());
        $this->assertEquals('userId', $sys->getPublishedBy()->getId());
        $this->assertInstanceOf(Link::class, $sys->getArchivedBy());
        $this->assertEquals('User', $sys->getArchivedBy()->getLinkType());
        $this->assertEquals('userId', $sys->getArchivedBy()->getId());

        $this->assertJsonFixtureEqualsJsonObject('Unit/system_properties.json', $sys);
    }
}
