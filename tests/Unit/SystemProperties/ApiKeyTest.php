<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Delivery\Unit\SystemProperties;

use Contentful\Management\SystemProperties\ApiKey;
use Contentful\Tests\Management\BaseTestCase;

class ApiKeyTest extends BaseTestCase
{
    public function testAll()
    {
        $fixture = $this->getParsedFixture('serialize.json');
        $sys = new ApiKey($fixture);

        $this->assertSame('0fzYxkdlzzatS7EhcEN6Gq', $sys->getId());
        $this->assertSame('ApiKey', $sys->getType());

        $this->assertSame('2018-07-30T15:56:23Z', (string) $sys->getUpdatedAt());
        $this->assertSame('2018-07-30T15:56:23Z', (string) $sys->getUpdatedAt());
        $this->assertLink('4Q3e6duhma7V6czH7UXHzE', 'User', $sys->getCreatedBy());
        $this->assertLink('4Q3e6duhma7V6czH7UXHzE', 'User', $sys->getUpdatedBy());
        $this->assertSame(1, $sys->getVersion());
        $this->assertLink('pmgjoasidv3w', 'Space', $sys->getSpace());

        $this->assertJsonStructuresAreEqual($fixture, $sys);
    }
}
