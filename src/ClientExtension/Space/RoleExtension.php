<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\ClientExtension\Space;

use Contentful\Core\Resource\ResourceArray;
use Contentful\Management\Query;
use Contentful\Management\Resource\ResourceInterface;
use Contentful\Management\Resource\Role as ResourceClass;

/**
 * RoleExtension trait.
 *
 * This extension is supposed to be applied to Client class which provides a `fetchResource` method.
 *
 * @method ResourceInterface|ResourceArray fetchResource(string $class, array $parameters, Query $query = null, ResourceInterface $resource = null)
 */
trait RoleExtension
{
    /**
     * Returns a Role resource.
     *
     * @param string $spaceId
     * @param string $roleId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/roles/role
     */
    public function getRole(string $spaceId, string $roleId): ResourceClass
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
            'role' => $roleId,
        ]);
    }

    /**
     * Returns a ResourceArray object containing Role resources.
     *
     * @param string     $spaceId
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/roles/roles-collection
     */
    public function getRoles(string $spaceId, Query $query = \null): ResourceArray
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
        ], $query);
    }
}
