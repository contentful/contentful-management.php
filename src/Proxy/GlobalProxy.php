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

/**
 * GlobalProxy class.
 *
 * This class is meant to represent a "fake" proxy for resources which do not logically belong to any other proxy.
 */
class GlobalProxy implements ProxyInterface
{
    use ApiActionTrait;

    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function toResource()
    {
        throw new \LogicException('Can not convert a global proxy to a resource');
    }

    /**
     * {@inheritdoc}
     */
    protected function getProxyParameters(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getClient(): Client
    {
        return $this->client;
    }
}
