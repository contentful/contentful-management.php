<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\ClientExtension\User;

use Contentful\Core\Resource\ResourceArray;
use Contentful\Management\Query;
use Contentful\Management\Resource\PersonalAccessToken as ResourceClass;
use Contentful\Management\Resource\ResourceInterface;

/**
 * PersonalAccessTokenExtension trait.
 *
 * This extension is supposed to be applied to Client class which provides a `fetchResource` method.
 *
 * @method ResourceInterface|ResourceArray fetchResource(string $class, array $parameters, Query $query = null, ResourceInterface $resource = null)
 */
trait PersonalAccessTokenExtension
{
    /**
     * Returns a PersonalAccessToken resource.
     *
     * @param string $personalAccessTokenId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/personal-access-tokens/personal-access-token
     */
    public function getPersonalAccessToken(string $personalAccessTokenId): ResourceClass
    {
        return $this->fetchResource(ResourceClass::class, [
            'personalAccessToken' => $personalAccessTokenId,
        ]);
    }

    /**
     * Returns a ResourceArray object containing PersonalAccessToken resources.
     *
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/personal-access-tokens/personal-access-tokens-collection
     */
    public function getPersonalAccessTokens(Query $query = \null): ResourceArray
    {
        return $this->fetchResource(ResourceClass::class, [], $query);
    }
}
