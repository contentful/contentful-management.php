<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
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
     * @expectedException \Error
     */
    public function testInvalidCreation()
    {
        new EntrySnapshot();
    }

    /**
     * This test is meaningless.
     * It's only here because private, empty constructors are not correctly picked by code coverage.
     */
    public function testConstructor()
    {
        $class = new \ReflectionClass(EntrySnapshot::class);
        $object = $class->newInstanceWithoutConstructor();
        $constructor = $class->getConstructor();
        $constructor->setAccessible(true);
        $constructor->invoke($object);

        $this->markTestAsPassed();
    }

    /**
     * @return EntrySnapshot
     */
    public function testJsonSerialize(): EntrySnapshot
    {
        $resource = (new ResourceBuilder())
            ->build($this->getParsedFixture('serialize.json'))
        ;

        $this->assertJsonFixtureEqualsJsonObject('Integration/Resource/EntrySnapshot/serialize.json', $resource);

        return $resource;
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
            ]], $entrySnapshot)
        ;
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
