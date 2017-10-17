<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\Integration\Resource;

use Contentful\Management\Resource\EntrySnapshot;
use Contentful\Management\ResourceBuilder;
use Contentful\Tests\Management\BaseTestCase;

class EntrySnapshotTest extends BaseTestCase
{
    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Class "Contentful\Management\Resource\EntrySnapshot" can only be instantiated as a result of an API call, manual creation is not allowed.
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

        $this->assertJsonFixtureEqualsJsonObject('Integration/Resource/entry_snapshot.json', $entrySnapshot);

        return $entrySnapshot;
    }

    /**
     * @param EntrySnapshot $entrySnapshot
     *
     * @depends testJsonSerialize
     * @expectedException \LogicException
     * @expectedExceptionMessage Trying to update resource object in mapper of type "Contentful\Management\Mapper\EntrySnapshot", but only creation from scratch is supported.
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

    /**
     * @param EntrySnapshot $entrySnapshot
     *
     * @depends testJsonSerialize
     * @expectedException \LogicException
     * @expectedExceptionMessage Trying to convert object of class "Contentful\Management\Resource\EntrySnapshot" to a request body format, but operation is not supported on this class.
     */
    public function testInvalidConversionToRequestBody(EntrySnapshot $entrySnapshot)
    {
        $entrySnapshot->asRequestBody();
    }
}
