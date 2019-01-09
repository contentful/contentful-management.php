<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management;

use Contentful\Core\Api\Link;
use Contentful\Core\Api\LinkResolverInterface;
use Contentful\Core\Resource\ResourceInterface;

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
    public function resolveLink(Link $link, array $parameters = []): ResourceInterface
    {
        $config = $this->configuration->getLinkConfigFor($link->getLinkType());
        $uri = $this->requestUriBuilder->build($config, $parameters, $link->getId());

        /** @var ResourceInterface $resource */
        $resource = $this->client->request('GET', $uri, [
            'host' => $config['host'] ?? \null,
        ]);

        return $resource;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveLinkCollection(array $links, array $parameters = []): array
    {
        return \array_map(function (Link $link) use ($parameters) {
            return $this->resolveLink($link, $parameters);
        }, $links);
    }
}
