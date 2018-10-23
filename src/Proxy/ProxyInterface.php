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

interface ProxyInterface
{
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
    public function create(CreatableInterface $resource, string $resourceId = '');

    /**
     * Archives the given resource.
     *
     * @param ArchivableInterface $resource
     */
    public function archive(ArchivableInterface $resource);

    /**
     * Unarchives the given resource.
     *
     * @param ArchivableInterface $resource
     */
    public function unarchive(ArchivableInterface $resource);

    /**
     * Deletes the given resource.
     *
     * @param DeletableInterface $resource
     */
    public function delete(DeletableInterface $resource);

    /**
     * Publishes the given resource.
     *
     * @param PublishableInterface $resource
     */
    public function publish(PublishableInterface $resource);

    /**
     * Unpublishes the given resource.
     *
     * @param PublishableInterface $resource
     */
    public function unpublish(PublishableInterface $resource);

    /**
     * Unpublishes the given resource.
     *
     * @param UpdatableInterface $resource
     */
    public function update(UpdatableInterface $resource);

    /**
     * Resolves a Contentful link scoped to the current proxy.
     *
     * @param Link     $link
     * @param string[] $parameters
     *
     * @return ResourceInterface
     */
    public function resolveLink(Link $link, array $parameters = []): ResourceInterface;

    /**
     * Returns the resource which corresponds to this proxy.
     *
     * @return ResourceInterface
     */
    public function toResource();

    /**
     * @return Client
     */
    public function getClient(): Client;
}
