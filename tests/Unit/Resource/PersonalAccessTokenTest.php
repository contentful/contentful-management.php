<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Management\Resource;

use Contentful\Management\Resource\PersonalAccessToken;
use PHPUnit\Framework\TestCase;

class PersonalAccessTokenTest extends TestCase
{
    public function testGetSetData()
    {
        $personalAccessToken = new PersonalAccessToken();

        $personalAccessToken->setName('Test token');
        $this->assertEquals('Test token', $personalAccessToken->getName());

        $personalAccessToken->setReadOnly(true);
        $this->assertTrue($personalAccessToken->isReadOnly());

        $this->assertNull($personalAccessToken->getRevokedAt());
        $this->assertNull($personalAccessToken->getToken());
    }

    public function testJsonSerialize()
    {
        $personalAccessToken = new PersonalAccessToken('Test token', true);

        $json = '{"sys":{"type":"PersonalAccessToken"},"name":"Test token","scopes":["content_management_read"]}';

        $this->assertJsonStringEqualsJsonString($json, json_encode($personalAccessToken));
    }
}
