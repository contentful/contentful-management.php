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
use Contentful\Management\Resource\Entry as ResourceClass;
use Contentful\Management\Resource\ResourceInterface;
use Contentful\ResourceArray;

/**
 * Entries class.
 *
 * This class is used as a proxy for doing operations related to entries.
 *
 * @method ResourceInterface create(\Contentful\Management\Resource\Entry $resource, string $resourceId = null)
 * @method ResourceInterface update(\Contentful\Management\Resource\Entry $resource)
 * @method ResourceInterface delete(\Contentful\Management\Resource\Entry|string $resource, int $version = null)
 * @method ResourceInterface archive(\Contentful\Management\Resource\Entry|string $resource, int $version = null)
 * @method ResourceInterface unarchive(\Contentful\Management\Resource\Entry|string $resource, int $version = null)
 * @method ResourceInterface publish(\Contentful\Management\Resource\Entry|string $resource, int $version = null)
 * @method ResourceInterface unpublish(\Contentful\Management\Resource\Entry|string $resource, int $version = null)
 */
class Entry extends BaseProxy
{
    /**
     * {@inheritdoc}
     */
    protected function getResourceUri(array $values): string
    {
        return \rtrim(\strtr('spaces/'.$this->spaceId.'/entries/{resourceId}', $values), '/');
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabledMethods(): array
    {
        return ['create', 'update', 'delete', 'archive', 'unarchive', 'publish', 'unpublish'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getCreateAdditionalHeaders(ResourceInterface $resource): array
    {
        return ['X-Contentful-Content-Type' => $resource->getSystemProperties()->getContentType()->getId()];
    }

    /**
     * Returns an Entry object which corresponds to the given resource ID in Contentful.
     *
     * @param string $resourceId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/entries/entry
     */
    public function get(string $resourceId): ResourceClass
    {
        return $this->getResource([
            '{resourceId}' => $resourceId,
        ]);
    }

    /**
     * Returns a ResourceArray object containing Entry objects.
     *
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/entries/entries-collection
     */
    public function getAll(Query $query = null): ResourceArray
    {
        return $this->getResource([
            '{resourceId}' => '',
        ], $query);
    }
}
