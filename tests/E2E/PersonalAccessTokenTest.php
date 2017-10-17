<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Management\ApiDateTime;
use Contentful\Management\Query;
use Contentful\Management\Resource\PersonalAccessToken;
use Contentful\Tests\Management\BaseTestCase;

class PersonalAccessTokenTest extends BaseTestCase
{
    /**
     * @vcr e2e_personal_access_token_create_get_revoke.json
     */
    public function testCreateGetRevoke()
    {
        $client = $this->getUnboundClient();

        $personalAccessToken = new PersonalAccessToken('Test access token', true);
        $client->personalAccessToken->create($personalAccessToken);

        $this->assertNotNull($personalAccessToken->getToken());
        $this->assertEquals('Test access token', $personalAccessToken->getName());
        $this->assertTrue($personalAccessToken->isReadOnly());

        $personalAccessToken = $client->personalAccessToken->get($personalAccessToken->getId());

        $this->assertNull($personalAccessToken->getToken());
        $this->assertEquals('Test access token', $personalAccessToken->getName());
        $this->assertTrue($personalAccessToken->isReadOnly());

        $personalAccessToken->revoke();
        $this->assertInstanceOf(ApiDateTime::class, $personalAccessToken->getRevokedAt());
    }

    /**
     * @vcr e2e_personal_access_token_get_collection.json
     */
    public function testGetCollection()
    {
        $query = (new Query())
            ->setLimit(1);
        $personalAccessTokens = $this->getUnboundClient()->personalAccessToken->getAll($query);

        $this->assertCount(1, $personalAccessTokens);

        $personalAccessToken = $personalAccessTokens[0];
        $this->assertNull($personalAccessToken->getToken());
        $this->assertNull($personalAccessToken->getRevokedAt());
        $this->assertEquals('TravisCI', $personalAccessToken->getName());
        $this->assertFalse($personalAccessToken->isReadOnly());
    }

    /**
     * @expectedException \Contentful\Management\Exception\InvalidProxyActionException
     * @expectedExceptionMessage Trying to perform invalid action "revoke" on proxy "Contentful\Management\Proxy\PersonalAccessToken" with argument of class "stdClass".
     */
    public function testInvalidRevokeObject()
    {
        $proxy = $this->getUnboundClient()->personalAccessToken;

        $proxy->revoke(new \stdClass());
    }
}
