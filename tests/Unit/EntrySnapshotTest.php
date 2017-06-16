<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\Unit;

use Contentful\Management\ResourceBuilder;

class EntrySnapshotTest extends \PHPUnit_Framework_TestCase
{
    public function testJsonSerialize()
    {
        $builder = new ResourceBuilder();

         $data = [
            'sys' => [
                'type' => 'Snapshot',
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
        ];

        $entrySnapshot = $builder->buildObjectsFromRawData($data);
        $json = '{"snapshot":{"sys":{"type":"Entry"},"fields":{"name":{"en-US":"Consuela Bananahammock"},"jobTitle":{"en-US":"Princess"}}},"sys":{"type":"Snapshot","snapshotEntityType":"Entry"}}';

        $this->assertJsonStringEqualsJsonString($json, json_encode($entrySnapshot));
    }
}
