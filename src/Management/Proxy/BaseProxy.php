<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Proxy;

use Contentful\Link;
use Contentful\Management\Client;
use Contentful\Management\Exception\InvalidProxyActionException;
use Contentful\Management\Query;
use Contentful\Management\Resource\Behavior\Archivable;
use Contentful\Management\Resource\Behavior\Creatable;
use Contentful\Management\Resource\Behavior\Deletable;
use Contentful\Management\Resource\Behavior\Publishable;
use Contentful\Management\Resource\Behavior\Updatable;
use Contentful\Management\Resource\ResourceInterface;
use Contentful\ResourceArray;

/**
 * BaseProxy class.
 */
abstract class BaseProxy
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string|null
     */
    protected $spaceId;

    /**
     * Whether the current proxy requires a space ID for working properly.
     * The default value is true, but a proxy object can set it to false.
     *
     * @var bool
     */
    protected $requiresSpaceId = true;

    /**
     * BaseProxy constructor.
     *
     * @param Client      $client
     * @param string|null $spaceId
     */
    public function __construct(Client $client, string $spaceId = null)
    {
        $this->client = $client;

        if ($this->requiresSpaceId && $spaceId === null) {
            throw new \RuntimeException(\sprintf(
                'Trying to access proxy "%s" which requires a space ID, but none is given.',
                static::class
            ));
        }

        $this->spaceId = $spaceId;
    }

    /**
     * Acts as a whitelister for protected methods.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @throws \LogicException
     *
     * @return ResourceInterface
     */
    public function __call(string $name, array $arguments)
    {
        if (\in_array($name, $this->getEnabledMethods())) {
            return $this->{$name}(...$arguments);
        }

        throw new InvalidProxyActionException(static::class, $name);
    }

    /**
     * Returns the string to be used as baseUri option,
     * or null otherwise.
     *
     * @return string|null
     */
    protected function getBaseUri()
    {
        return null;
    }

    /**
     * Shortcut for resolving links.
     *
     * @param Link $link
     *
     * @return ResourceInterface
     */
    public function resolveLink(Link $link)
    {
        return $this->client->resolveLink($link);
    }

    /**
     * @param array                  $values
     * @param Query|null             $query
     * @param ResourceInterface|null $resource
     *
     * @return ResourceInterface|ResourceArray
     */
    protected function getResource(array $values, Query $query = null, ResourceInterface $resource = null)
    {
        $resource = $this->client->getResource($this->getResourceUri($values), $query, [
            'baseUri' => $this->getBaseUri(),
        ], $resource);

        if ($resource instanceof ResourceInterface) {
            return $resource->setProxy($this);
        }

        // If it's not an instance of ResourceInterface, it's an instance of ResourceArray
        foreach ($resource as $resourceObject) {
            $resourceObject->setProxy($this);
        }

        return $resource;
    }

    /**
     * @param string                   $method
     * @param string                   $uriSuffix
     * @param ResourceInterface|string $resource
     * @param int|null                 $version
     * @param mixed                    $body
     *
     * @return ResourceInterface
     */
    protected function requestResource(string $method, string $uriSuffix, $resource, int $version = null, $body = null)
    {
        $version = $resource instanceof ResourceInterface
            ? $resource->getSystemProperties()->getVersion()
            : $version;
        $target = $resource instanceof ResourceInterface
            ? $resource
            : null;
        $uri = $this->getResourceUri(['{resourceId}' => $this->getResourceId($resource)]);

        $this->client->requestResource($method, $uri.$uriSuffix, [
            'additionalHeaders' => ['X-Contentful-Version' => $version],
            'body' => $body,
            'baseUri' => $this->getBaseUri(),
        ], $target);

        if (\is_object($target)) {
            $target->setProxy($this);
        }

        return $target;
    }

    /**
     * @param ResourceInterface|string $resource
     *
     * @return string
     */
    protected function getResourceId($resource): string
    {
        if ($resource instanceof ResourceInterface) {
            return $resource->getId();
        }

        return $resource;
    }

    /**
     * Creates a resource.
     *
     * @param Creatable   $resource
     * @param string|null $resourceId
     */
    protected function create(Creatable $resource, string $resourceId = null)
    {
        $uri = $this->getResourceUri([
            '{resourceId}' => $resourceId ?: '',
        ]);
        $method = $resourceId === null ? 'POST' : 'PUT';

        $this->client->requestResource($method, $uri, [
            'body' => $resource->asRequestBody(),
            'additionalHeaders' => $this->getCreateAdditionalHeaders($resource),
            'baseUri' => $this->getBaseUri(),
        ], $resource);

        $resource->setProxy($this);

        return $resource;
    }

    /**
     * Override this method in a proxy class if special headers are to be defined upon creation.
     *
     * @param ResourceInterface $resource
     *
     * @return string[]
     */
    protected function getCreateAdditionalHeaders(ResourceInterface $resource): array
    {
        return [];
    }

    /**
     * Updates a resource.
     *
     * @param Updatable $resource
     */
    protected function update(Updatable $resource)
    {
        return $this->requestResource('PUT', '', $resource, null, $resource->asRequestBody());
    }

    /**
     * Deletes a resource.
     *
     * @param ResourceInterface|string $resource Either a resource object, or a resource ID
     * @param int|null                 $version  Null if $resouce is an object
     */
    protected function delete($resource, int $version = null)
    {
        if (\is_object($resource) && !($resource instanceof Deletable)) {
            throw new InvalidProxyActionException(static::class, 'delete', $resource);
        }

        return $this->requestResource('DELETE', '', $resource, $version);
    }

    /**
     * Archives a resource.
     *
     * @param ResourceInterface|string $resource Either a resource object, or a resource ID
     * @param int|null                 $version  Null if $resouce is an object
     */
    protected function archive($resource, int $version = null)
    {
        if (\is_object($resource) && !($resource instanceof Archivable)) {
            throw new InvalidProxyActionException(static::class, 'archive', $resource);
        }

        return $this->requestResource('PUT', '/archived', $resource, $version);
    }

    /**
     * Unarchives a resource.
     *
     * @param ResourceInterface|string $resource Either a resource object, or a resource ID
     * @param int|null                 $version  Null if $resouce is an object
     */
    protected function unarchive($resource, int $version = null)
    {
        if (\is_object($resource) && !($resource instanceof Archivable)) {
            throw new InvalidProxyActionException(static::class, 'unarchive', $resource);
        }

        return $this->requestResource('DELETE', '/archived', $resource, $version);
    }

    /**
     * Publishes a resource.
     *
     * @param ResourceInterface|string $resource Either a resource object, or a resource ID
     * @param int|null                 $version  Null if $resouce is an object
     */
    protected function publish($resource, int $version = null)
    {
        if (\is_object($resource) && !($resource instanceof Publishable)) {
            throw new InvalidProxyActionException(static::class, 'publish', $resource);
        }

        return $this->requestResource('PUT', '/published', $resource, $version);
    }

    /**
     * Unpublishes a resource.
     *
     * @param ResourceInterface|string $resource Either a resource object, or a resource ID
     * @param int|null                 $version  Null if $resouce is an object
     */
    protected function unpublish($resource, int $version = null)
    {
        if (\is_object($resource) && !($resource instanceof Publishable)) {
            throw new InvalidProxyActionException(static::class, 'unpublish', $resource);
        }

        return $this->requestResource('DELETE', '/published', $resource, $version);
    }

    /**
     * Returns the URI for the current proxy type.
     *
     * @param array $values
     *
     * @return string
     */
    abstract protected function getResourceUri(array $values): string;

    /**
     * An array of enabled methods for the current proxy.
     *
     * @return string[]
     */
    abstract public function getEnabledMethods(): array;
}
