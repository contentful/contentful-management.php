<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\Integration;

use Contentful\Management\ResourceBuilder;
use Contentful\Management\Resource\ContentTypeSnapshot;
use PHPUnit\Framework\TestCase;

class ContentTypeSnapshotTest extends TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testInvalidCreation()
    {
        new ContentTypeSnapshot();
    }

    public function testJsonSerialize()
    {
        $contentTypeSnapshot = (new ResourceBuilder())->buildObjectsFromRawData([
            'sys' => [
                'type' => 'Snapshot',
                'snapshotType' => 'publish',
                'snapshotEntityType' => 'ContentType',
            ],
            'snapshot' => [
                'name' => 'Versioned Content Type',
                'displayField' => 'title',
                'fields' => [
                    [
                        'name' => 'Title',
                        'id' => 'title',
                        'type' => 'Symbol',
                    ],
                    [
                        'name' => 'Description',
                        'id' => 'description',
                        'type' => 'Text',
                    ],
                ],
                'sys' => [
                    'id' => 'versionedContentType',
                    'type' => 'ContentType',
                ],
            ],
        ]);

        $json = '{"sys":{"type":"Snapshot","snapshotType":"publish","snapshotEntityType":"ContentType"},"snapshot":{"name":"Versioned Content Type","displayField":"title","fields":[{"name":"Title","id":"title","type":"Symbol"},{"name":"Description","id":"description","type":"Text"}],"sys":{"id":"versionedContentType","type":"ContentType"}}}';

        $this->assertJsonStringEqualsJsonString($json, json_encode($contentTypeSnapshot));
    }
}
