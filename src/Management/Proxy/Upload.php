<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Proxy;

use Contentful\Management\Client;
use Contentful\Management\Resource\ResourceInterface;
use Contentful\Management\Resource\Upload as ResourceClass;

/**
 * Upload class.
 *
 * @method ResourceInterface create(\Contentful\Management\Resource\Upload $resource, string $resourceId = null)
 * @method ResourceInterface delete(\Contentful\Management\Resource\Upload|string $resource, int $version = null)
 */
class Upload extends BaseProxy
{
    /**
     * {@inheritdoc}
     */
    protected function getResourceUri(array $values): string
    {
        return \rtrim(\strtr('spaces/'.$this->spaceId.'/uploads/{resourceId}', $values), '/');
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabledMethods(): array
    {
        return ['create', 'delete'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getCreateAdditionalHeaders(ResourceInterface $resource): array
    {
        return ['Content-Type' => 'application/octet-stream'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getBaseUri(): string
    {
        return Client::URI_UPLOAD;
    }

    /**
     * Returns an Asset object which corresponds to the given resource ID in Contentful.
     *
     * @param string $resourceId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/uploads/retrieving-an-upload
     */
    public function get(string $resourceId): ResourceClass
    {
        return $this->getResource([
            '{resourceId}' => $resourceId,
        ]);
    }
}
