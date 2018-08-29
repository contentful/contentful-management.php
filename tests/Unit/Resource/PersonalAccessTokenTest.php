<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource;

use Contentful\Management\Resource\PersonalAccessToken;
use Contentful\Tests\Management\BaseTestCase;

class PersonalAccessTokenTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $personalAccessToken = new PersonalAccessToken();

        $personalAccessToken->setName('Test token');
        $this->assertSame('Test token', $personalAccessToken->getName());

        $personalAccessToken->setReadOnly(\true);
        $this->assertTrue($personalAccessToken->isReadOnly());

        $this->assertNull($personalAccessToken->getRevokedAt());
        $this->assertNull($personalAccessToken->getToken());
    }

    /**
     * @expectedException        \LogicException
     * @expectedExceptionMessage Trying to revoke a token which has not been fetched from the API.
     */
    public function testRevokeWithoutCreation()
    {
        $personalAccessToken = new PersonalAccessToken();

        $personalAccessToken->revoke();
    }

    public function testJsonSerialize()
    {
        $personalAccessToken = new PersonalAccessToken('Test token', \true);

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/personal_access_token.json', $personalAccessToken);
    }
}
