<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\E2E\Management;

use Contentful\Management\Query;
use Contentful\Management\Resource\PersonalAccessToken;
use Contentful\Tests\End2EndTestCase;

class PersonalAccessTokenTest extends End2EndTestCase
{
    /**
     * @vcr e2e_personal_access_token_create_get_revoke.json
     */
    public function testCreateGetRevoke()
    {
        $personalAccessToken = new PersonalAccessToken('Test access token', true);
        $this->client->createPersonalAccessToken($personalAccessToken);

        $this->assertNotNull($personalAccessToken->getToken());
        $this->assertEquals('Test access token', $personalAccessToken->getName());
        $this->assertTrue($personalAccessToken->isReadOnly());

        $personalAccessToken = $this->client->getPersonalAccessToken($personalAccessToken->getSystemProperties()->getId());

        $this->assertNull($personalAccessToken->getToken());
        $this->assertEquals('Test access token', $personalAccessToken->getName());
        $this->assertTrue($personalAccessToken->isReadOnly());

        $this->client->revokePersonalAccessToken($personalAccessToken);
        $this->assertInstanceOf(\DateTimeImmutable::class, $personalAccessToken->getRevokedAt());
    }

    /**
     * @vcr e2e_personal_access_token_get_collection.json
     */
    public function testGetCollection()
    {
        $query = (new Query())
            ->setLimit(1);
        $personalAccessTokens = $this->client->getPersonalAccessTokens($query);

        $this->assertCount(1, $personalAccessTokens);

        $personalAccessToken = $personalAccessTokens[0];
        $this->assertNull($personalAccessToken->getToken());
        $this->assertNull($personalAccessToken->getRevokedAt());
        $this->assertEquals('TravisCI', $personalAccessToken->getName());
        $this->assertFalse($personalAccessToken->isReadOnly());
    }
}
