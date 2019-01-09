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
use Contentful\Management\Resource\DeliveryApiKey as ResourceClass;
use Contentful\Management\Resource\ResourceInterface;

/**
 * DeliveryApiKeyExtension trait.
 *
 * This extension is supposed to be applied to Client class which provides a `fetchResource` method.
 *
 * @method ResourceInterface|ResourceArray fetchResource(string $class, array $parameters, Query $query = null, ResourceInterface $resource = null)
 */
trait DeliveryApiKeyExtension
{
    /**
     * Returns a DeliveryApiKey resource.
     *
     * @param string $spaceId
     * @param string $deliveryApiKeyId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/api-keys
     */
    public function getDeliveryApiKey(string $spaceId, string $deliveryApiKeyId): ResourceClass
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
            'deliveryApiKey' => $deliveryApiKeyId,
        ]);
    }

    /**
     * Returns a ResourceArray object containing DeliveryApiKey objects.
     *
     * @param string     $spaceId
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/api-keys/api-keys-collection
     */
    public function getDeliveryApiKeys(string $spaceId, Query $query = \null): ResourceArray
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
        ], $query);
    }
}
