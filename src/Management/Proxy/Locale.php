<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Proxy;

use Contentful\Management\Resource\Locale as ResourceClass;
use Contentful\ResourceArray;

/**
 * Locale class.
 *
 * This class is used as a proxy for doing operations related to locales.
 *
 * @method ResourceInterface create(\Contentful\Management\Resource\Locale $resource, string $resourceId = null)
 * @method ResourceInterface update(\Contentful\Management\Resource\Locale $resource)
 * @method ResourceInterface delete(\Contentful\Management\Resource\Locale|string $resource, int $version = null)
 */
class Locale extends BaseProxy
{
    /**
     * {@inheritdoc}
     */
    public function getResourceUri(array $values): string
    {
        return \rtrim(\strtr('spaces/'.$this->spaceId.'/locales/{resourceId}', $values), '/');
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabledMethods(): array
    {
        return ['create', 'update', 'delete'];
    }

    /**
     * Returns a Locale object which corresponds to the given resource ID in Contentful.
     *
     * @param string $resourceId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/locales/locale
     */
    public function get(string $resourceId): ResourceClass
    {
        return $this->getResource([
            '{resourceId}' => $resourceId,
        ]);
    }

    /**
     * Returns a ResourceArray object containing Locale objects.
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/locales/locale-collection
     */
    public function getAll(): ResourceArray
    {
        return $this->getResource([
            '{resourceId}' => '',
        ]);
    }
}
