<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Core\Api\Link;
use Contentful\Management\Client;
use Contentful\Management\SystemProperties;
use function GuzzleHttp\json_encode as guzzle_json_encode;

/**
 * BaseResource class.
 */
abstract class BaseResource implements ResourceInterface
{
    /**
     * @var SystemProperties
     */
    protected $sys;

    /**
     * @var Client|null
     */
    protected $client;

    /**
     * Initialize system properties.
     *
     * @param string $type The system type
     * @param array  $sys
     */
    protected function initialize(string $type, array $sys = [])
    {
        $sys['type'] = $type;
        $sys['id'] = '';
        $this->sys = new SystemProperties($sys);
    }

    /**
     * {@inheritdoc}
     */
    public function getSystemProperties(): SystemProperties
    {
        return $this->sys;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return $this->sys->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return $this->sys->getType();
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
