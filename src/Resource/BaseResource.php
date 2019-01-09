<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Core\Api\Link;
use Contentful\Core\Resource\SystemPropertiesInterface;
use Contentful\Management\Client;
use function GuzzleHttp\json_encode as guzzle_json_encode;

/**
 * BaseResource class.
 */
abstract class BaseResource implements ResourceInterface
{
    /**
     * @var SystemPropertiesInterface
     */
    protected $sys;

    /**
     * @var Client|null
     */
    protected $client;

    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return $this->getSystemProperties()->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return $this->getSystemProperties()->getType();
    }

    /**
     * {@inheritdoc}
     */
    public function asLink(): Link
    {
        return new Link($this->getId(), $this->getType());
    }

    /**
     * {@inheritdoc}
     */
    public function asRequestBody()
    {
        $body = $this->jsonSerialize();

        unset($body['sys']);

        return guzzle_json_encode((object) $body, \JSON_UNESCAPED_UNICODE);
    }

    /**
     * Sets the current Client object instance.
     * This is done automatically when performing API calls,
     * so it shouldn't be used manually.
     *
     * @param Client $client
     *
     * @return static
     */
    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }
}
