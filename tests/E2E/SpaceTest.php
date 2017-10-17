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
use Contentful\Tests\Management\BaseTestCase;

class SpaceTest extends BaseTestCase
{
    /**
     * @vcr e2e_space_get_one.json
     */
    public function testGetSpace()
    {
        $space = $this->getUnboundClient()->space->get($this->defaultSpaceId);

        $this->assertInstanceOf(Space::class, $space);
        $this->assertEquals(new Link($this->defaultSpaceId, 'Space'), $space->asLink());
        $sys = $space->getSystemProperties();
        $this->assertEquals($this->defaultSpaceId, $sys->getId());
        $this->assertEquals('Space', $sys->getType());
        $this->assertEquals(new ApiDateTime('2017-05-18T13:35:42'), $sys->getCreatedAt());
        $this->assertEquals(new ApiDateTime('2017-07-06T10:12:00'), $sys->getUpdatedAt());
        $this->assertEquals(5, $sys->getVersion());
        $this->assertEquals(new Link('5wTIctqPekjOi9TGctNW7L', 'User'), $sys->getCreatedBy());
        $this->assertEquals(new Link('1CECdY5ZhqJapGieg6QS9P', 'User'), $sys->getUpdatedBy());
        $this->assertEquals('PHP CMA', $space->getName());
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
