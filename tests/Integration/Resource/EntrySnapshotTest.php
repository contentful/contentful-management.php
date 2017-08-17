<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Integration\Resource;

use Contentful\Management\ResourceBuilder;
use Contentful\Management\Resource\EntrySnapshot;
use PHPUnit\Framework\TestCase;

class EntrySnapshotTest extends TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testInvalidCreation()
    {
        new EntrySnapshot();
    }

    /**
     * @return EntrySnapshot
     */
    public function testJsonSerialize(): EntrySnapshot
    {
        $entrySnapshot = (new ResourceBuilder())->build([
            'sys' => [
                'type' => 'Snapshot',
                'snapshotType' => 'publish',
                'snapshotEntityType' => 'Entry',
            ],
            'snapshot' => [
                'fields' => [
                    'name' => [
                        'en-US' => 'Consuela Bananahammock',
                    ],
                    'jobTitle' => [
                        'en-US' => 'Princess',
                    ],
                ],
                'sys' => [
                    'type' => 'Entry',
                    'contentType' => [
                        'sys' => [
                            'linkType' => 'ContentType',
                            'id' => 'person',
                        ],
                    ],
                ],
            ],
        ]);

        $json = '{"snapshot":{"sys":{"type":"Entry","contentType":{"sys":{"linkType":"ContentType","id":"person","type":"Link"}}},"fields":{"name":{"en-US":"Consuela Bananahammock"},"jobTitle":{"en-US":"Princess"}}},"sys":{"type":"Snapshot","snapshotType":"publish","snapshotEntityType":"Entry"}}';

        $this->assertJsonStringEqualsJsonString($json, json_encode($entrySnapshot));

        return $entrySnapshot;
    }

    /**
     * @param EntrySnapshot $entrySnapshot
     *
     * @depends testJsonSerialize
     * @expectedException \LogicException
     */
    public function testInvalidUpdate(EntrySnapshot $entrySnapshot)
    {
        (new ResourceBuilder())
            ->build(['sys' => [
                'type' => 'Snapshot',
                'snapshotType' => 'publish',
                'snapshotEntityType' => 'Entry',
            ]], $entrySnapshot);
    }
}
