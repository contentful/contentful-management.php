<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Delivery\Unit\SystemProperties;

use Contentful\Management\SystemProperties\Snapshot;
use Contentful\Tests\Management\BaseTestCase;

class SnapshotTest extends BaseTestCase
{
    public function testAll()
    {
        $fixture = $this->getParsedFixture('serialize.json');
        $sys = new Snapshot($fixture);

        $this->assertSame('3omuk8H8M8wUuqHhxddXtp', $sys->getId());
        $this->assertSame('Snapshot', $sys->getType());

        $this->assertLink('34luz0flcmxt', 'Space', $sys->getSpace());
        $this->assertLink('master', 'Environment', $sys->getEnvironment());

        $this->assertSame('2017-06-14T14:11:20.189Z', (string) $sys->getUpdatedAt());
        $this->assertSame('2017-06-14T14:11:20.189Z', (string) $sys->getUpdatedAt());

        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getCreatedBy());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getUpdatedBy());

        $this->assertSame('publish', $sys->getSnapshotType());
        $this->assertSame('Entry', $sys->getSnapshotEntityType());

        $this->assertJsonStructuresAreEqual($fixture, $sys);
    }
}
