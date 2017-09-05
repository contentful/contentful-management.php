<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Proxy;

use Contentful\Management\Query;
use Contentful\Management\Resource\PersonalAccessToken as ResourceClass;
use Contentful\ResourceArray;

/**
 * PersonalAccessToken class.
 *
 * This class is used as a proxy for doing operations related to personal access tokens.
 */
class PersonalAccessToken extends BaseProxy
{
    /**
     * {@inheritdoc}
     */
    protected $requiresSpaceId = false;

    /**
     * {@inheritdoc}
     */
    public function getResourceUri(array $values): string
    {
        return rtrim(strtr('users/me/access_tokens/{resourceId}', $values), '/');
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabledMethods(): array
    {
        return ['create', 'revoke'];
    }

    /**
     * Returns a Locale object which corresponds to the given resource ID in Contentful.
     *
     * @param string $resourceId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/personal-access-tokens/personal-access-token
     */
    public function get(string $resourceId): ResourceClass
    {
        return $this->getResource([
            '{resourceId}' => $resourceId,
        ]);
    }

    /**
     * Returns a ResourceArray object containing Locale objects.
     *
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/personal-access-tokens/personal-access-tokens-collection
     */
    public function getAll(Query $query = null): ResourceArray
    {
        return $this->getResource([
            '{resourceId}' => '',
        ], $query);
    }

    /**
     * Revokes a personal access token.
     *
     * @param ResourceClass $resource
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/personal-access-tokens/token-revoking
     */
    public function revoke(ResourceClass $resource)
    {
        return $this->requestResource('PUT', '/revoked', $resource);
    }
}
