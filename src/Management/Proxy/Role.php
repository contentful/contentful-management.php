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
use Contentful\Management\Resource\Role as ResourceClass;
use Contentful\ResourceArray;

/**
 * Role class.
 *
 * This class is used as a proxy for doing operations related to roles.
 *
 * @method ResourceInterface create(\Contentful\Management\Resource\Role $resource, string $resourceId = null)
 * @method ResourceInterface update(\Contentful\Management\Resource\Role $resource)
 * @method ResourceInterface delete(\Contentful\Management\Resource\Role|string $resource, int $version = null)
 */
class Role extends BaseProxy
{
    /**
     * {@inheritdoc}
     */
    public function getResourceUri(array $values): string
    {
        return \rtrim(\strtr('spaces/'.$this->spaceId.'/roles/{resourceId}', $values), '/');
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabledMethods(): array
    {
        return ['create', 'update', 'delete'];
    }

    /**
     * Returns a Role object which corresponds to the given resource ID in Contentful.
     *
     * @param string $resourceId
     *
     * @return Role
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/roles/role
     */
    public function get(string $resourceId): ResourceClass
    {
        return $this->getResource([
            '{resourceId}' => $resourceId,
        ]);
    }

    /**
     * Returns a ResourceArray object containing Role objects.
     *
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/roles/roles-collection
     */
    public function getAll(Query $query = null): ResourceArray
    {
        return $this->getResource([
            '{resourceId}' => '',
        ], $query);
    }
}
