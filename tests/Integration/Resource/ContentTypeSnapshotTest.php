<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Integration\Resource;

use Contentful\Management\Resource\ContentTypeSnapshot;
use Contentful\Management\ResourceBuilder;
use Contentful\Tests\Management\BaseTestCase;

class ContentTypeSnapshotTest extends BaseTestCase
{
    public function testInvalidCreation()
    {
        $this->expectException(\Error::class);
        new ContentTypeSnapshot();
    }

    /**
     * This test is meaningless.
     * It's only here because private, empty constructors are not correctly picked by code coverage.
     */
    public function testConstructor()
    {
        $class = new \ReflectionClass(ContentTypeSnapshot::class);
        $object = $class->newInstanceWithoutConstructor();
        $constructor = $class->getConstructor();
        $constructor->setAccessible(true);
        $constructor->invoke($object);

        $this->markTestAsPassed();
    }

    public function testJsonSerialize(): ContentTypeSnapshot
    {
        $resource = (new ResourceBuilder())
            ->build($this->getParsedFixture('serialize.json'))
        ;

        $this->assertJsonFixtureEqualsJsonObject('Integration/Resource/ContentTypeSnapshot/serialize.json', $resource);

        return $resource;
    }

    /**
     * @depends testJsonSerialize
     */
    public function testInvalidUpdate(ContentTypeSnapshot $contentTypeSnapshot)
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Trying to update resource object in mapper of type \"Contentful\Management\Mapper\ContentTypeSnapshot\", but only creation from scratch is supported.");

        (new ResourceBuilder())
            ->build(['sys' => [
                'type' => 'Snapshot',
                'snapshotEntityType' => 'ContentType',
            ]], $contentTypeSnapshot)
        ;
    }

    /**
     * @depends testJsonSerialize
     */
    public function testInvalidConversionToRequestBody(ContentTypeSnapshot $contentTypeSnapshot)
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Trying to convert object of class \"Contentful\Management\Resource\ContentTypeSnapshot\" to a request body format, but operation is not supported on this class.");

        $contentTypeSnapshot->asRequestBody();
    }
}
