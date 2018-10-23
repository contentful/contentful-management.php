<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Proxy;

use Contentful\Management\Client;
use Contentful\Management\Resource\Space;

/**
 * SpaceProxy class.
 *
 * This class works as a lazy reference to a space resource.
 * You can use it for most space-related needs, such as fetching roles, API keys and webhooks,
 * or creating them (attaching them to this space).
 *
 * To access this class, you can use the convenience method found in a client object.
 *
 * ``` php
 * $space = $client->getSpaceProxy($spaceId);
 * ```
 *
 * The methods provided are very similar to the getX() methods you will find in an actual space resource object.
 * The main difference is that when fetching a space resource, you will actually call the API,
 * whereas with this proxy, you're just holding a reference to a certain space.
 * This is the reason why in most situations you should prefer using
 * a SpaceProxy rather than a space resource.
 *
 * ``` php
 * // Only the entries query will be made
 * $space = $client->getSpaceProxy($spaceId);
 * $entries = $space->getEntries();
 *
 * // Two queries will be made
 * $space = $client->getSpace($spaceId);
 * $entries = $space->getEntries();
 * ```
 */
class SpaceProxy implements ProxyInterface
{
    use ApiActionTrait;

    use Extension\SpaceProxyExtension;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $spaceId;

    /**
     * @param Client $client
     * @param string $spaceId
     */
    public function __construct(Client $client, string $spaceId)
    {
        $this->client = $client;
        $this->spaceId = $spaceId;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSpaceId()
    {
        return $this->spaceId;
    }

    /**
     * Returns a proxy to an environment resource.
     * Useful for all environment-scoped operations.
     *
     * @param string $environmentId
     *
     * @return EnvironmentProxy
     */
    public function getEnvironmentProxy(string $environmentId): EnvironmentProxy
    {
        return new EnvironmentProxy($this->client, $this->spaceId, $environmentId);
    }

    /**
     * Returns the Space resource which corresponds to this proxy.
     *
     * @return Space
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/spaces/space
     */
    public function toResource(): Space
    {
        return $this->client->getSpace($this->spaceId);
    }

    /**
     * {@inheritdoc}
     */
    protected function getProxyParameters(): array
    {
        return [
            'space' => $this->spaceId,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getClient(): Client
    {
        return $this->client;
    }
}
