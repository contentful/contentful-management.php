<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Exception\NotFoundException;
use Contentful\Link;
use Contentful\Management\ApiDateTime;
use Contentful\Management\Query;
use Contentful\Management\Resource\Space;
use Contentful\ResourceArray;
use Contentful\Tests\Management\End2EndTestCase;

class SpaceTest extends End2EndTestCase
{
    /**
     * @vcr e2e_space_get_one.json
     */
    public function testGetSpace()
    {
        $space = $this->getUnboundClient()->space->get($this->readOnlySpaceId);

        $this->assertInstanceOf(Space::class, $space);
        $this->assertEquals(new Link($this->readOnlySpaceId, 'Space'), $space->asLink());
        $sys = $space->getSystemProperties();
        $this->assertEquals($this->readOnlySpaceId, $sys->getId());
        $this->assertEquals('Space', $sys->getType());
        $this->assertEquals(new ApiDateTime('2013-06-23T19:02:00'), $sys->getCreatedAt());
        $this->assertEquals(new ApiDateTime('2016-02-25T09:57:25'), $sys->getUpdatedAt());
        $this->assertEquals(4, $sys->getVersion());
        $this->assertEquals(new Link('7BslKh9TdKGOK41VmLDjFZ', 'User'), $sys->getCreatedBy());
        $this->assertEquals(new Link('7BslKh9TdKGOK41VmLDjFZ', 'User'), $sys->getUpdatedBy());
        $this->assertEquals('Contentful Example API', $space->getName());
    }

    /**
     * @vcr e2e_space_get_collection.json
     */
    public function testGetSpaces()
    {
        $client = $this->getUnboundClient();

        $spaces = $client->space->getAll();

        $this->assertInstanceOf(ResourceArray::class, $spaces);
        $this->assertInstanceOf(Space::class, $spaces[0]);

        $query = (new Query())
            ->setLimit(1);
        $spaces = $client->space->getAll($query);
        $this->assertInstanceOf(Space::class, $spaces[0]);
        $this->assertCount(1, $spaces);
    }

    /**
     * @vcr e2e_space_create_delete_non_english_locale.json
     */
    public function testCreateDeleteSpaceNonEnglishLocale()
    {
        $client = $this->getUnboundClient();

        $space = new Space('PHP CMA Italian Test Space', $this->testOrganizationId, 'it-IT');

        $client->space->create($space);

        $spaceId = $space->getId();
        $this->assertNotNull($spaceId);

        $space->delete();

        try {
            $client->space->get($spaceId);
        } catch (\Exception $e) {
            $this->assertInstanceOf(NotFoundException::class, $e);
            $this->assertEquals('The resource could not be found.', $e->getMessage());
        }
    }

    /**
     * @vcr e2e_space_create_update_delete.json
     */
    public function testCreateUpdateDeleteSpace()
    {
        $client = $this->getUnboundClient();

        $space = new Space('PHP CMA Test Space', $this->testOrganizationId);

        $client->space->create($space);

        $spaceId = $space->getId();
        $this->assertNotNull($spaceId);
        $this->assertEquals('PHP CMA Test Space', $space->getName());
        $this->assertEquals(1, $space->getSystemProperties()->getVersion());

        $space->setName('PHP CMA Test Space - Updated');

        $space->update();
        $this->assertSame($space, $space);
        $this->assertEquals($spaceId, $space->getId());
        $this->assertEquals('PHP CMA Test Space - Updated', $space->getName());
        $this->assertEquals(2, $space->getSystemProperties()->getVersion());

        $space->delete();

        try {
            $client->space->get($spaceId);
        } catch (\Exception $e) {
            $this->assertInstanceOf(NotFoundException::class, $e);
            $this->assertEquals('The resource could not be found.', $e->getMessage());
        }
    }
}
