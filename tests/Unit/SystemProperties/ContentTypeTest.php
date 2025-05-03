<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Delivery\Unit\SystemProperties;

use Contentful\Management\SystemProperties\ContentType;
use Contentful\Tests\Management\BaseTestCase;

class ContentTypeTest extends BaseTestCase
{
    public function testAll()
    {
        $fixture = $this->getParsedFixture('serialize.json');
        $sys = new ContentType($fixture);

        $this->assertSame('cat', $sys->getId());
        $this->assertSame('ContentType', $sys->getType());

        $this->assertLink('34luz0flcmxt', 'Space', $sys->getSpace());
        $this->assertLink('master', 'Environment', $sys->getEnvironment());

        $this->assertSame('2017-10-17T12:23:16.461Z', (string) $sys->getCreatedAt());
        $this->assertSame('2017-10-17T12:23:46.396Z', (string) $sys->getUpdatedAt());
        $this->assertSame('2017-10-17T12:23:46.365Z', (string) $sys->getFirstPublishedAt());
        $this->assertSame('2017-10-17T12:23:46.365Z', (string) $sys->getPublishedAt());

        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getCreatedBy());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getUpdatedBy());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getPublishedBy());

        $this->assertSame(1, $sys->getPublishedCounter());
        $this->assertSame(3, $sys->getVersion());
        $this->assertSame(2, $sys->getPublishedVersion());

        $this->assertJsonStructuresAreEqual($fixture, $sys);
    }
}
