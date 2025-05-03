<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Delivery\Unit\SystemProperties;

use Contentful\Management\SystemProperties\Upload;
use Contentful\Tests\Management\BaseTestCase;

class UploadTest extends BaseTestCase
{
    public function testAll()
    {
        $fixture = $this->getParsedFixture('serialize.json');
        $sys = new Upload($fixture);

        $this->assertSame('kGrVn541RH6UzTOCojFqo', $sys->getId());
        $this->assertSame('Upload', $sys->getType());

        $this->assertLink('pmgjoasidv3w', 'Space', $sys->getSpace());

        $this->assertSame('2018-08-01T08:19:42Z', (string) $sys->getCreatedAt());
        $this->assertSame('2018-08-03T00:00:00Z', (string) $sys->getExpiresAt());

        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getCreatedBy());

        $this->assertJsonStructuresAreEqual($fixture, $sys);
    }
}
