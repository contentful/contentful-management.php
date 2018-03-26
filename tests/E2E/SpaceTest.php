<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Core\Exception\NotFoundException;
use Contentful\Core\Resource\ResourceArray;
use Contentful\Management\Query;
use Contentful\Management\Resource\Space;
use Contentful\Tests\Management\BaseTestCase;

class SpaceTest extends BaseTestCase
{
    /**
     * @vcr e2e_space_get_one.json
     */
    public function testGetOne()
    {
        $space = $this->getClient()->getSpace($this->defaultSpaceId);

        $this->assertInstanceOf(Space::class, $space);
        $this->assertLink($this->defaultSpaceId, 'Space', $space->asLink());
        $sys = $space->getSystemProperties();
        $this->assertSame($this->defaultSpaceId, $sys->getId());
        $this->assertSame('Space', $sys->getType());
        $this->assertSame('2017-05-18T13:35:42Z', (string) $sys->getCreatedAt());
        $this->assertSame('2017-07-06T10:12:00Z', (string) $sys->getUpdatedAt());
        $this->assertSame(5, $sys->getVersion());
        $this->assertLink('5wTIctqPekjOi9TGctNW7L', 'User', $sys->getCreatedBy());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getUpdatedBy());
        $this->assertSame('PHP CMA', $space->getName());
    }

    /**
     * @vcr e2e_space_get_collection.json
     */
    public function testGetCollection()
    {
        $client = $this->getClient();

        $spaces = $client->getSpaces();

        $this->assertInstanceOf(ResourceArray::class, $spaces);
        $this->assertInstanceOf(Space::class, $spaces[0]);

        $query = (new Query())
            ->setLimit(1);
        $spaces = $client->getSpaces($query);
        $this->assertInstanceOf(Space::class, $spaces[0]);
        $this->assertCount(1, $spaces);
    }

    /**
     * @vcr e2e_space_create_delete_non_english_locale.json
     */
    public function testCreateDeleteNonEnglishLocale()
    {
        $client = $this->getClient();

        $space = new Space('PHP CMA Italian Test Space', $this->testOrganizationId, 'it-IT');

        $client->create($space);

        $spaceId = $space->getId();
        $this->assertNotNull($spaceId);

        $space->delete();

        try {
            $client->getSpace($spaceId);
        } catch (\Exception $exception) {
            $this->assertInstanceOf(NotFoundException::class, $exception);
            $this->assertSame('The resource could not be found.', $exception->getMessage());
        }
    }

    /**
     * @vcr e2e_space_create_update_delete.json
     */
    public function testCreateUpdateDeleteSpace()
    {
        $client = $this->getClient();

        $space = new Space('PHP CMA Test Space', $this->testOrganizationId);

        $client->create($space);

        $spaceId = $space->getId();
        $this->assertNotNull($spaceId);
        $this->assertSame('PHP CMA Test Space', $space->getName());
        $this->assertSame(1, $space->getSystemProperties()->getVersion());

        $space->setName('PHP CMA Test Space - Updated');

        $space->update();
        $this->assertSame($spaceId, $space->getId());
        $this->assertSame('PHP CMA Test Space - Updated', $space->getName());
        $this->assertSame(2, $space->getSystemProperties()->getVersion());

        $space->delete();

        try {
            $client->getSpace($spaceId);

            $this->fail('Trying to get a non-existing space did not throw an exception');
        } catch (\Exception $exception) {
            $this->assertInstanceOf(NotFoundException::class, $exception);
            $this->assertSame('The resource could not be found.', $exception->getMessage());
        }
    }
}
