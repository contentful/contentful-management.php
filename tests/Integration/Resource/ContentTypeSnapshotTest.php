<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\Integration\Resource;

use Contentful\Management\Resource\ContentTypeSnapshot;
use Contentful\Management\ResourceBuilder;
use Contentful\Tests\Management\BaseTestCase;

class ContentTypeSnapshotTest extends BaseTestCase
{
    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Class "Contentful\Management\Resource\ContentTypeSnapshot" can only be instantiated as a result of an API call, manual creation is not allowed.
     */
    public function testInvalidCreation()
    {
        new ContentTypeSnapshot();
    }

    /**
     * @return ContentTypeSnapshot
     */
    public function testJsonSerialize(): ContentTypeSnapshot
    {
        $contentTypeSnapshot = (new ResourceBuilder())->build([
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

        $this->assertJsonFixtureEqualsJsonObject('Integration/Resource/content_type_snapshot.json', $contentTypeSnapshot);

        return $contentTypeSnapshot;
    }

    /**
     * @param ContentTypeSnapshot $contentTypeSnapshot
     *
     * @depends testJsonSerialize
     * @expectedException \LogicException
     * @expectedExceptionMessage Trying to update resource object in mapper of type "Contentful\Management\Mapper\ContentTypeSnapshot", but only creation from scratch is supported.
     */
    public function testInvalidUpdate(ContentTypeSnapshot $contentTypeSnapshot)
    {
        (new ResourceBuilder())
            ->build(['sys' => [
                'type' => 'Snapshot',
                'snapshotEntityType' => 'ContentType',
            ]], $contentTypeSnapshot);
    }

    /**
     * @param ContentTypeSnapshot $contentTypeSnapshot
     *
     * @depends testJsonSerialize
     * @expectedException \LogicException
     * @expectedExceptionMessage Trying to convert object of class "Contentful\Management\Resource\ContentTypeSnapshot" to a request body format, but operation is not supported on this class.
     */
    public function testInvalidConversionToRequestBody(ContentTypeSnapshot $contentTypeSnapshot)
    {
        $contentTypeSnapshot->asRequestBody();
    }
}
