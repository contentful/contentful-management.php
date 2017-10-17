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
use Contentful\Management\Resource\ContentType as ResourceClass;
use Contentful\ResourceArray;

/**
 * ContentType class.
 *
 * This class is used as a proxy for doing operations related to content types.
 *
 * @method ResourceInterface create(\Contentful\Management\Resource\ContentType $resource, string $resourceId = null)
 * @method ResourceInterface update(\Contentful\Management\Resource\ContentType $resource)
 * @method ResourceInterface delete(\Contentful\Management\Resource\ContentType|string $resource, int $version = null)
 * @method ResourceInterface publish(\Contentful\Management\Resource\ContentType|string $resource, int $version = null)
 * @method ResourceInterface unpublish(\Contentful\Management\Resource\ContentType|string $resource, int $version = null)
 */
class ContentType extends BaseProxy
{
    /**
     * {@inheritdoc}
     */
    protected function getResourceUri(array $values): string
    {
        return \rtrim(\strtr('spaces/'.$this->spaceId.'/content_types/{resourceId}', $values), '/');
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabledMethods(): array
    {
        return ['create', 'update', 'delete', 'publish', 'unpublish'];
    }

    /**
     * Returns a ContentType object which corresponds to the given resource ID in Contentful.
     *
     * @param string $resourceId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/content-types/content-type
     */
    public function get(string $resourceId): ResourceClass
    {
        return $this->getResource([
            '{resourceId}' => $resourceId,
        ]);
    }

    /**
     * Returns a ResourceArray object containing ContentType objects.
     *
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/content-types/content-type-collection
     */
    public function getAll(Query $query = null): ResourceArray
    {
        return $this->getResource([
            '{resourceId}' => '',
        ], $query);
    }
}
