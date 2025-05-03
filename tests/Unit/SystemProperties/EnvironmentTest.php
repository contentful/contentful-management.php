<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Delivery\Unit\SystemProperties;

use Contentful\Management\SystemProperties\Environment;
use Contentful\Tests\Management\BaseTestCase;

class EnvironmentTest extends BaseTestCase
{
    public function testAll()
    {
        $fixture = $this->getParsedFixture('serialize.json');
        $sys = new Environment($fixture);

        $this->assertSame('master', $sys->getId());
        $this->assertSame('Environment', $sys->getType());

        $this->assertLink('34luz0flcmxt', 'Space', $sys->getSpace());
        $this->assertLink('ready', 'Status', $sys->getStatus());

        $this->assertSame('2017-12-07T11:07:09Z', (string) $sys->getCreatedAt());
        $this->assertSame('2018-03-19T09:59:34Z', (string) $sys->getUpdatedAt());

        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getCreatedBy());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getUpdatedBy());

        $this->assertSame(3, $sys->getVersion());

        $this->assertJsonStructuresAreEqual($fixture, $sys);
    }
}
