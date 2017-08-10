<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\Unit\Resource;

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

    public function testJsonSerialize()
    {
        $entrySnapshot = (new ResourceBuilder())->buildObjectsFromRawData([
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
                ],
            ],
        ]);

        $json = '{"snapshot":{"sys":{"type":"Entry"},"fields":{"name":{"en-US":"Consuela Bananahammock"},"jobTitle":{"en-US":"Princess"}}},"sys":{"type":"Snapshot","snapshotType":"publish","snapshotEntityType":"Entry"}}';

        $this->assertJsonStringEqualsJsonString($json, json_encode($entrySnapshot));
    }
}
