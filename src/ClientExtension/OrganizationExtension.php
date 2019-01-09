<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\ClientExtension;

use Contentful\Core\Resource\ResourceArray;
use Contentful\Management\Query;
use Contentful\Management\Resource\Organization as ResourceClass;
use Contentful\Management\Resource\ResourceInterface;

/**
 * OrganizationExtension trait.
 *
 * This extension is supposed to be applied to Client class which provides a `fetchResource` method.
 *
 * @method ResourceInterface|ResourceArray fetchResource(string $class, array $parameters, Query $query = null, ResourceInterface $resource = null)
 */
trait OrganizationExtension
{
    /**
     * Returns a ResourceArray object containing Organization resources.
     *
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/organizations
     */
    public function getOrganizations(Query $query = \null): ResourceArray
    {
        return $this->fetchResource(ResourceClass::class, [], $query);
    }
}
