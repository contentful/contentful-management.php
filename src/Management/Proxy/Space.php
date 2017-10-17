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
use Contentful\Management\Resource\ResourceInterface;
use Contentful\Management\Resource\Space as ResourceClass;
use Contentful\ResourceArray;

/**
 * Space class.
 *
 * This class is used as a proxy for doing operations related to spaces.
 *
 * @method ResourceInterface create(\Contentful\Management\Resource\Space $resource, string $resourceId = null)
 * @method ResourceInterface update(\Contentful\Management\Resource\Space $resource)
 * @method ResourceInterface delete(\Contentful\Management\Resource\Space|string $resource, int $version = null)
 */
class Space extends BaseProxy
{
    /**
     * {@inheritdoc}
     */
    protected $requiresSpaceId = false;

    /**
     * {@inheritdoc}
     */
    protected function getResourceUri(array $values): string
    {
        return \rtrim(\strtr('spaces/{resourceId}', $values), '/');
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabledMethods(): array
    {
        return ['create', 'update', 'delete'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getCreateAdditionalHeaders(ResourceInterface $resource): array
    {
        return ['X-Contentful-Organization' => $resource->getOrganizationId()];
    }

    /**
     * Returns a Space object which corresponds to the given resource ID in Contentful.
     *
     * @param string $resourceId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/spaces/space
     */
    public function get(string $resourceId): ResourceClass
    {
        return $this->getResource([
            '{resourceId}' => $resourceId,
        ]);
    }

    /**
     * Returns a ResourceArray object containing Space objects.
     *
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/spaces/spaces-collection
     */
    public function getAll(Query $query = null): ResourceArray
    {
        return $this->getResource([
            '{resourceId}' => '',
        ], $query);
    }
}
