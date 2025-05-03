<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Delivery\Unit\SystemProperties;

use Contentful\Management\SystemProperties\Entry;
use Contentful\Tests\Management\BaseTestCase;

class EntryTest extends BaseTestCase
{
    public function testAll()
    {
        $fixture = $this->getParsedFixture('serialize.json');
        $sys = new Entry($fixture);

        $this->assertSame('4OuC4z6qs0yEWMeqkGmokw', $sys->getId());
        $this->assertSame('Entry', $sys->getType());

        $this->assertLink('34luz0flcmxt', 'Space', $sys->getSpace());
        $this->assertLink('master', 'Environment', $sys->getEnvironment());
        $this->assertLink('fantasticCreature', 'ContentType', $sys->getContentType());

        $this->assertSame('2017-08-22T11:50:19.841Z', (string) $sys->getCreatedAt());
        $this->assertSame('2017-08-22T11:50:27.087Z', (string) $sys->getUpdatedAt());
        $this->assertSame('2017-08-22T11:50:26.990Z', (string) $sys->getFirstPublishedAt());
        $this->assertSame('2017-08-22T11:50:26.990Z', (string) $sys->getPublishedAt());
        $this->assertSame('2017-08-22T11:50:26.990Z', (string) $sys->getArchivedAt());

        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getCreatedBy());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getUpdatedBy());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getPublishedBy());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getArchivedBy());

        $this->assertSame(1, $sys->getPublishedCounter());
        $this->assertSame(6, $sys->getVersion());
        $this->assertSame(5, $sys->getPublishedVersion());
        $this->assertSame(5, $sys->getArchivedVersion());

        $this->assertTrue($sys->isArchived());
        $this->assertTrue($sys->isPublished());
        $this->assertFalse($sys->isDraft());
        $this->assertFalse($sys->isUpdated());

        $this->assertJsonStructuresAreEqual($fixture, $sys);
    }
}
