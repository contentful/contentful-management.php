<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Delivery\Unit\SystemProperties;

use Contentful\Management\SystemProperties\Space;
use Contentful\Tests\Management\BaseTestCase;

class SpaceTest extends BaseTestCase
{
    public function testAll()
    {
        $fixture = $this->getParsedFixture('serialize.json');
        $sys = new Space($fixture);

        $this->assertSame('34luz0flcmxt', $sys->getId());
        $this->assertSame('Space', $sys->getType());

        $this->assertSame('2017-05-18T13:35:42Z', (string) $sys->getCreatedAt());
        $this->assertSame('2018-08-20T14:19:50Z', (string) $sys->getUpdatedAt());

        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getCreatedBy());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getUpdatedBy());

        $this->assertSame(7, $sys->getVersion());

        $this->assertJsonStructuresAreEqual($fixture, $sys);
    }
}
