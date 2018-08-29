<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management;

use Contentful\Core\Api\Link;
use Contentful\Core\Api\LinkResolverInterface;
use Contentful\Management\Resource\ResourceInterface;

class LinkResolver implements LinkResolverInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var ApiConfiguration
     */
    private $configuration;

    /**
     * @var RequestUriBuilder
     */
    private $requestUriBuilder;

    public function __construct(Client $client, ApiConfiguration $configuration, RequestUriBuilder $requestUriBuilder)
    {
        $this->client = $client;
        $this->configuration = $configuration;
        $this->requestUriBuilder = $requestUriBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveLink(Link $link, array $parameters = [])
    {
        $config = $this->configuration->getLinkConfigFor($link->getLinkType());
        $uri = $this->requestUriBuilder->build($config, $parameters, $link->getId());

        /** @var ResourceInterface $resource */
        $resource = $this->client->makeRequest('GET', $uri, [
            'baseUri' => $config['baseUri'] ?? \null,
        ]);

        return $resource;
    }
}
