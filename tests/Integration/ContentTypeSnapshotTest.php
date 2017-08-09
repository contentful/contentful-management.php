<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\Integration;

use Contentful\Management\ResourceBuilder;
use PHPUnit\Framework\TestCase;

class ContentTypeSnapshotTest extends TestCase
{
    public function testJsonSerialize()
    {
        $builder = new ResourceBuilder();

        $data = [
            'sys' => [
                'type' => 'Snapshot',
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
        ];

        $entrySnapshot = $builder->buildObjectsFromRawData($data);
        $json = '{"sys":{"type":"Snapshot","snapshotEntityType":"ContentType"},"snapshot":{"name":"Versioned Content Type","displayField":"title","fields":[{"name":"Title","id":"title","type":"Symbol"},{"name":"Description","id":"description","type":"Text"}],"sys":{"id":"versionedContentType","type":"ContentType"}}}';

        $this->assertJsonStringEqualsJsonString($json, json_encode($entrySnapshot));
    }
}
