<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\E2E;

use Contentful\Exception\NotFoundException;
use Contentful\Link;
use Contentful\Management\Query;
use Contentful\Management\Resource\Space;
use Contentful\ResourceArray;
use Contentful\Tests\End2EndTestCase;

class SpaceTest extends End2EndTestCase
{
    /**
     * @vcr e2e_space_get.json
     */
    public function testGetSpace()
    {
        $space = $this->client->getSpace($this->readOnlySpaceId);

        $this->assertInstanceOf(Space::class, $space);
        $this->assertEquals(new Link($this->readOnlySpaceId, 'Space'), $space->asLink());
        $sys = $space->getSystemProperties();
        $this->assertEquals($this->readOnlySpaceId, $sys->getId());
        $this->assertEquals('Space', $sys->getType());
        $this->assertEquals(new \DateTimeImmutable('2013-06-23T19:02:00'), $sys->getCreatedAt());
        $this->assertEquals(new \DateTimeImmutable('2016-02-25T09:57:25'), $sys->getUpdatedAt());
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
        $spaces = $this->client->getSpaces();

        $this->assertInstanceOf(ResourceArray::class, $spaces);
        $this->assertInstanceOf(Space::class, $spaces[0]);

        $query = (new Query())
            ->setLimit(1);
        $spaces = $this->client->getSpaces($query);
        $this->assertInstanceOf(Space::class, $spaces[0]);
        $this->assertCount(1, $spaces);
    }

    /**
     * @vcr e2e_space_create_delete_non_english_locale.json
     */
    public function testCreateDeleteSpaceNonEnglishLocale()
    {
        $space = new Space('PHP CMA German Test Space');

        $this->client->createSpace($space, $this->testOrganizationId, 'de-DE');

        $id = $space->getSystemProperties()->getId();
        $this->assertNotNull($id);

        $this->client->deleteSpace($space);

        try {
            $this->client->getSpace($id);
        } catch (\Exception $e) {
            $this->assertInstanceOf(NotFoundException::class, $e);
        }
    }

    /**
     * @vcr e2e_space_create_update_delete.json
     */
    public function testCreateUpdateDeleteSpace()
    {
        $space = new Space('PHP CMA Test Space');

        $this->client->createSpace($space, $this->testOrganizationId);

        $id = $space->getSystemProperties()->getId();
        $this->assertNotNull($id);
        $this->assertEquals('PHP CMA Test Space', $space->getName());
        $this->assertEquals(1, $space->getSystemProperties()->getVersion());

        $space->setName('PHP CMA Test Space - Updated');

        $this->client->updateSpace($space);
        $this->assertSame($space, $space);
        $this->assertEquals($id, $space->getSystemProperties()->getId());
        $this->assertEquals('PHP CMA Test Space - Updated', $space->getName());
        $this->assertEquals(2, $space->getSystemProperties()->getVersion());

        $this->client->deleteSpace($space);

        try {
            $this->client->getSpace($id);
        } catch (\Exception $e) {
            $this->assertInstanceOf(NotFoundException::class, $e);
        }
    }
}
