<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Delivery\Unit\SystemProperties;

use Contentful\Management\SystemProperties\Asset;
use Contentful\Tests\Management\BaseTestCase;

class AssetTest extends BaseTestCase
{
    public function testAll()
    {
        $fixture = $this->getParsedFixture('serialize.json');
        $sys = new Asset($fixture);

        $this->assertSame('2TEG7c2zYkSSuKmsqEwCS', $sys->getId());
        $this->assertSame('Asset', $sys->getType());

        $this->assertLink('34luz0flcmxt', 'Space', $sys->getSpace());
        $this->assertLink('master', 'Environment', $sys->getEnvironment());

        $this->assertSame('2017-08-22T15:42:20.969Z', (string) $sys->getUpdatedAt());
        $this->assertSame('2017-08-22T15:42:20.969Z', (string) $sys->getUpdatedAt());
        $this->assertNull($sys->getFirstPublishedAt());
        $this->assertNull($sys->getPublishedAt());

        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getCreatedBy());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getUpdatedBy());
        $this->assertNull($sys->getPublishedBy());

        $this->assertSame(11, $sys->getVersion());
        $this->assertNull($sys->getPublishedCounter());
        $this->assertNull($sys->getPublishedVersion());

        $this->assertFalse($sys->isPublished());

        $this->assertJsonStructuresAreEqual($fixture, $sys);
    }
}
