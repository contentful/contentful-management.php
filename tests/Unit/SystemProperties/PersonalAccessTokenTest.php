<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Delivery\Unit\SystemProperties;

use Contentful\Management\SystemProperties\PersonalAccessToken;
use Contentful\Tests\Management\BaseTestCase;

class PersonalAccessTokenTest extends BaseTestCase
{
    public function testAll()
    {
        $fixture = $this->getParsedFixture('serialize.json');
        $sys = new PersonalAccessToken($fixture);

        $this->assertJsonStructuresAreEqual($fixture, $sys);
    }
}
