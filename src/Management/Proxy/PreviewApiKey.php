<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Proxy;

use Contentful\Management\Query;
use Contentful\Management\Resource\PreviewApiKey as ResourceClass;
use Contentful\ResourceArray;

/**
 * PreviewApiKey class.
 *
 * This class is used as a proxy for doing operations related to preview api keys.
 */
class PreviewApiKey extends BaseProxy
{
    /**
     * {@inheritdoc}
     */
    protected function getResourceUri(array $values): string
    {
        return rtrim(strtr('spaces/'.$this->spaceId.'/preview_api_keys/{resourceId}', $values), '/');
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabledMethods(): array
    {
        return [];
    }

    /**
     * Returns a PreviewApiKey object which corresponds to the given resource ID in Contentful.
     *
     * @param string $resourceId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/api-keys
     */
    public function get(string $resourceId): ResourceClass
    {
        return $this->getResource([
            '{resourceId}' => $resourceId,
        ]);
    }

    /**
     * Returns a ResourceArray object containing DeliveryApiKey objects.
     *
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/api-keys/api-keys-collection
     */
    public function getAll(Query $query = null): ResourceArray
    {
        return $this->getResource([
            '{resourceId}' => '',
        ], $query);
    }
}
