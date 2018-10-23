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
use Contentful\Management\Resource\Environment;

/**
 * EnvironmentProxy class.
 *
 * This class works as a lazy reference to an environment resource.
 * You can use it for most environment-related needs, such as fetching entries, assets and content types,
 * or creating them (attaching them to this space).
 *
 * To access this class, you can use the convenience method found in a client object.
 *
 * ``` php
 * $environment = $client->getEnvironmentProxy($spaceId, $environmentId);
 * ```
 *
 * The methods provided are very similar to the getX() methods you will find in an actual environment resource object.
 * The main difference is that when fetching a environment resource, you will actually call the API,
 * whereas with this proxy, you're just holding a reference to a certain environment.
 * This is the reason why in most situations you should prefer using
 * an EnvironmentProxy rather than an environment resource.
 *
 * ``` php
 * // Only the entries query will be made
 * $environment = $client->getEnvironmentProxy($spaceId, $environmentId);
 * $entries = $environment->getEntries();
 *
 * // Two queries will be made
 * $environment = $client->getEnvironment($spaceId, $environmentId);
 * $entries = $environment->getEntries();
 * ```
 */
class EnvironmentProxy implements ProxyInterface
{
    use ApiActionTrait;

    use Extension\EnvironmentProxyExtension;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $spaceId;

    /**
     * @var string
     */
    private $environmentId;

    /**
     * @param Client $client
     * @param string $spaceId
     * @param string $environmentId
     */
    public function __construct(Client $client, string $spaceId, string $environmentId)
    {
        $this->client = $client;
        $this->spaceId = $spaceId;
        $this->environmentId = $environmentId;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSpaceId()
    {
        return $this->spaceId;
    }

    /**
     * {@inheritdoc}
     */
    protected function getEnvironmentId()
    {
        return $this->environmentId;
    }

    /**
     * Returns a proxy to a space resource.
     * Useful for all space-scoped operations.
     *
     * @return SpaceProxy
     */
    public function getSpaceProxy(): SpaceProxy
    {
        return new SpaceProxy($this->client, $this->spaceId);
    }

    /**
     * Returns the Environment resource which corresponds to this proxy.
     *
     * @return Environment
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/environments/environment
     */
    public function toResource(): Environment
    {
        return $this->client->getEnvironment($this->spaceId, $this->environmentId);
    }

    /**
     * {@inheritdoc}
     */
    protected function getProxyParameters(): array
    {
        return [
            'space' => $this->spaceId,
            'environment' => $this->environmentId,
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
