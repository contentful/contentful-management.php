<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Proxy;

use Contentful\Core\Api\Link;
use Contentful\Management\Client;
use Contentful\Management\Resource\Behavior\ArchivableInterface;
use Contentful\Management\Resource\Behavior\CreatableInterface;
use Contentful\Management\Resource\Behavior\DeletableInterface;
use Contentful\Management\Resource\Behavior\PublishableInterface;
use Contentful\Management\Resource\Behavior\UpdatableInterface;
use Contentful\Management\Resource\ResourceInterface;

/**
 * Trait ApiActionTrait.
 */
trait ApiActionTrait
{
    /**
     * Returns the parameters that define the current proxy.
     *
     * @return array
     */
    abstract protected function getProxyParameters(): array;

    /**
     * @return Client
     */
    abstract public function getClient(): Client;

    /**
     * Persists a new resource in Contentful.
     * This is a convenience method which just forwards to Client::create(),
     * but setting the `space` and `environment` keys to the current space and environment IDs in the parameters array.
     *
     * @param CreatableInterface $resource
     * @param string             $resourceId
     *
     * @see Client::create()
     */
    public function create(CreatableInterface $resource, string $resourceId = '')
    {
        $this->getClient()->create(
            $resource,
            $resourceId,
            $this->getProxyParameters()
        );
    }

    /**
     * Archives the given resource.
     *
     * @param ArchivableInterface $resource
     */
    public function archive(ArchivableInterface $resource)
    {
        $this->getClient()->requestWithResource($resource, 'PUT', '/archived', [
            'headers' => ['X-Contentful-Version' => $resource->getSystemProperties()->getVersion()],
        ]);
    }

    /**
     * Unarchives the given resource.
     *
     * @param ArchivableInterface $resource
     */
    public function unarchive(ArchivableInterface $resource)
    {
        $this->getClient()->requestWithResource($resource, 'DELETE', '/archived', [
            'headers' => ['X-Contentful-Version' => $resource->getSystemProperties()->getVersion()],
        ]);
    }

    /**
     * Deletes the given resource.
     *
     * @param DeletableInterface $resource
     */
    public function delete(DeletableInterface $resource)
    {
        $this->getClient()->requestWithResource($resource, 'DELETE');
    }

    /**
     * Publishes the current resource.
     *
     * @param PublishableInterface $resource
     */
    public function publish(PublishableInterface $resource)
    {
        $this->getClient()->requestWithResource($resource, 'PUT', '/published', [
            'headers' => ['X-Contentful-Version' => $resource->getSystemProperties()->getVersion()],
        ]);
    }

    /**
     * Unpublishes the current resource.
     *
     * @param PublishableInterface $resource
     */
    public function unpublish(PublishableInterface $resource)
    {
        $this->getClient()->requestWithResource($resource, 'DELETE', '/published', [
            'headers' => ['X-Contentful-Version' => $resource->getSystemProperties()->getVersion()],
        ]);
    }

    /**
     * Updates the current resource.
     *
     * @param UpdatableInterface $resource
     */
    public function update(UpdatableInterface $resource)
    {
        $this->getClient()->requestWithResource($resource, 'PUT', '', [
            'headers' => ['X-Contentful-Version' => $resource->getSystemProperties()->getVersion()],
            'body' => $resource->asRequestBody(),
        ]);
    }

    /**
     * Resolves a Contentful link scoped to the current proxy.
     *
     * @param Link     $link
     * @param string[] $parameters
     *
     * @return ResourceInterface
     */
    public function resolveLink(Link $link, array $parameters = []): ResourceInterface
    {
        return $this->getClient()->resolveLink($link, \array_merge(
            $parameters,
            $this->getProxyParameters()
        ));
    }
}
