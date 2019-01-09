<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Management\Proxy\Extension\EnvironmentProxyExtension;
use Contentful\Management\Resource\Behavior\CreatableInterface;
use Contentful\Management\Resource\Behavior\DeletableTrait;
use Contentful\Management\Resource\Behavior\UpdatableTrait;
use Contentful\Management\SystemProperties\Environment as SystemProperties;
use function GuzzleHttp\json_encode as guzzle_json_encode;

/**
 * Environment class.
 *
 * This class represents a resource with type "Environment" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/environments
 */
class Environment extends BaseResource implements CreatableInterface
{
    use EnvironmentProxyExtension,
        DeletableTrait,
        UpdatableTrait;

    /**
     * @var SystemProperties
     */
    protected $sys;

    /**
     * @var string
     */
    protected $name;

    /**
     * Environment constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
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
    public function jsonSerialize(): array
    {
        return [
            'sys' => $this->sys,
            'name' => $this->name,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function asRequestBody(): string
    {
        $body = $this->jsonSerialize();

        unset($body['sys']);

        return guzzle_json_encode((object) $body, \JSON_UNESCAPED_UNICODE);
    }

    /**
     * {@inheritdoc}
     */
    public function asUriParameters(): array
    {
        return [
            'space' => $this->sys->getSpace()->getId(),
            'environment' => $this->sys->getId(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getHeadersForCreation(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function getSpaceId()
    {
        return $this->sys->getSpace()->getId();
    }

    /**
     * {@inheritdoc}
     */
    protected function getEnvironmentId()
    {
        return $this->sys->getId();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return static
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }
}
