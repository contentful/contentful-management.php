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
use Contentful\Management\Resource\Asset as ResourceClass;
use Contentful\ResourceArray;

/**
 * Asset class.
 *
 * @method ResourceInterface create(\Contentful\Management\Resource\Asset $resource, string $resourceId = null)
 * @method ResourceInterface update(\Contentful\Management\Resource\Asset $resource)
 * @method ResourceInterface delete(\Contentful\Management\Resource\Asset|string $resource, int $version = null)
 * @method ResourceInterface archive(\Contentful\Management\Resource\Asset|string $resource, int $version = null)
 * @method ResourceInterface unarchive(\Contentful\Management\Resource\Asset|string $resource, int $version = null)
 * @method ResourceInterface publish(\Contentful\Management\Resource\Asset|string $resource, int $version = null)
 * @method ResourceInterface unpublish(\Contentful\Management\Resource\Asset|string $resource, int $version = null)
 */
class Asset extends BaseProxy
{
    /**
     * {@inheritdoc}
     */
    protected function getResourceUri(array $values): string
    {
        return \rtrim(\strtr('spaces/'.$this->spaceId.'/assets/{resourceId}', $values), '/');
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabledMethods(): array
    {
        return ['create', 'update', 'delete', 'archive', 'unarchive', 'publish', 'unpublish', 'process'];
    }

    /**
     * Returns an Asset object which corresponds to the given resource ID in Contentful.
     *
     * @param string $resourceId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/assets/asset
     */
    public function get(string $resourceId): ResourceClass
    {
        return $this->getResource([
            '{resourceId}' => $resourceId,
        ]);
    }

    /**
     * Returns a ResourceArray object containing Asset objects.
     *
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/assets/assets-collection
     */
    public function getAll(Query $query = null): ResourceArray
    {
        return $this->getResource([
            '{resourceId}' => '',
        ], $query);
    }

    /**
     * @param ResourceClass $resource
     * @param string|null   $locale
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/assets/asset-processing
     */
    public function process(ResourceClass $resource, string $locale = null)
    {
        $locales = $locale
            ? [$locale]
            : \array_keys($resource->getFiles());

        foreach ($locales as $locale) {
            $this->requestResource('PUT', '/files/'.$locale.'/process', $resource);
        }

        return $this->getResource([
            '{resourceId}' => $resource->getId(),
        ], null, $resource);
    }
}
