<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Delivery\Unit\SystemProperties;

use Contentful\Management\SystemProperties\EditorInterface;
use Contentful\Tests\Management\BaseTestCase;

class EditorInterfaceTest extends BaseTestCase
{
    public function testAll()
    {
        $fixture = $this->getParsedFixture('serialize.json');
        $sys = new EditorInterface($fixture);

        $this->assertSame('default', $sys->getId());
        $this->assertSame('EditorInterface', $sys->getType());

        $this->assertLink('pmgjoasidv3w', 'Space', $sys->getSpace());
        $this->assertLink('master', 'Environment', $sys->getEnvironment());
        $this->assertLink('bookmark', 'ContentType', $sys->getContentType());

        $this->assertSame('2018-07-30T17:51:38.611Z', (string) $sys->getCreatedAt());
        $this->assertSame('2018-07-30T17:51:41.289Z', (string) $sys->getUpdatedAt());

        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getCreatedBy());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getUpdatedBy());

        $this->assertSame(2, $sys->getVersion());

        $this->assertJsonStructuresAreEqual($fixture, $sys);
    }
}
