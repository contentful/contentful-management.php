<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Proxy;

use Contentful\Management\Query;
use Contentful\Management\Resource\SpaceMembership as ResourceClass;
use Contentful\ResourceArray;

/**
 * SpaceMembership class.
 *
 * This class is used as a proxy for doing operations related to space memberships.
 *
 * @method ResourceInterface create(\Contentful\Management\Resource\SpaceMembership $resource, string $resourceId = null)
 * @method ResourceInterface update(\Contentful\Management\Resource\SpaceMembership $resource)
 * @method ResourceInterface delete(\Contentful\Management\Resource\SpaceMembership|string $resource, int $version = null)
 */
class SpaceMembership extends BaseProxy
{
    /**
     * {@inheritdoc}
     */
    public function getResourceUri(array $values): string
    {
        return \rtrim(\strtr('spaces/'.$this->spaceId.'/space_memberships/{resourceId}', $values), '/');
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabledMethods(): array
    {
        return ['create', 'update', 'delete'];
    }

    /**
     * Returns a SpaceMembership object which corresponds to the given resource ID in Contentful.
     *
     * @param string $resourceId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/space-memberships/space-membership
     */
    public function get(string $resourceId): ResourceClass
    {
        return $this->getResource([
            '{resourceId}' => $resourceId,
        ]);
    }

    /**
     * Returns a ResourceArray object containing SpaceMembership objects.
     *
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/space-memberships
     */
    public function getAll(Query $query = null): ResourceArray
    {
        return $this->getResource([
            '{resourceId}' => '',
        ], $query);
    }
}
