<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Core\Api\DateTimeImmutable;
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
        $client = $this->getClient();

        $personalAccessToken = new PersonalAccessToken('Test access token', true);
        $client->create($personalAccessToken);

        $this->assertNotNull($personalAccessToken->getToken());
        $this->assertSame('Test access token', $personalAccessToken->getName());
        $this->assertTrue($personalAccessToken->isReadOnly());

        $personalAccessToken = $client->getPersonalAccessToken($personalAccessToken->getId());

        $this->assertNull($personalAccessToken->getToken());
        $this->assertSame('Test access token', $personalAccessToken->getName());
        $this->assertTrue($personalAccessToken->isReadOnly());

        $personalAccessToken->revoke();
        $this->assertInstanceOf(DateTimeImmutable::class, $personalAccessToken->getRevokedAt());
    }

    /**
     * @vcr e2e_personal_access_token_get_collection.json
     */
    public function testGetCollection()
    {
        $personalAccessTokens = $this->getClient()->getPersonalAccessTokens();

        // This check is useful to make sure that
        // all space memberships objects were properly created.
        $this->assertCount($personalAccessTokens->getTotal(), $personalAccessTokens);

        $query = (new Query())
            ->setLimit(1)
        ;
        $personalAccessTokens = $this->getClient()->getPersonalAccessTokens($query);

        $this->assertCount(1, $personalAccessTokens);

        $personalAccessToken = $personalAccessTokens[0];
        $this->assertNull($personalAccessToken->getToken());
        $this->assertNull($personalAccessToken->getRevokedAt());
        $this->assertSame('TravisCI', $personalAccessToken->getName());
        $this->assertFalse($personalAccessToken->isReadOnly());
    }
}
