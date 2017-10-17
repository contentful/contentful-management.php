<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Link;
use Contentful\Management\Proxy\BaseProxy;
use Contentful\Management\SystemProperties;
use function GuzzleHttp\json_encode;

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
     * @var BaseProxy
     */
    protected $proxy;

    /**
     * BaseResource constructor.
     *
     * @param string $type The system type
     * @param array  $sys
     */
    protected function __construct(string $type, array $sys = [])
    {
        $sys['type'] = $type;
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
     * Shortcut for accessing the resource ID through its system properties.
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->sys->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function asLink(): Link
    {
        return new Link($this->sys->getId(), $this->sys->getType());
    }

    /**
     * Sets the current BaseProxy object instance.
     * This is done automatically when performing API calls,
     * so it shouldn't be used manually.
     *
     * @param BaseProxy $proxy
     *
     * @return static
     */
    public function setProxy(BaseProxy $proxy)
    {
        $this->proxy = $proxy;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function asRequestBody()
    {
        $body = $this->jsonSerialize();

        unset($body['sys']);

        return json_encode((object) $body, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Shortcut for forwarding methods to the current proxy, using the current object as argument.
     *
     * ``` php
     * // Instead of
     * $client->asset->publish($asset);
     * // You can use
     * $asset->publish();
     * ```
     *
     * @param string $name
     * @param array  $arguments
     */
    public function __call(string $name, array $arguments)
    {
        if (\in_array($name, $this->proxy->getEnabledMethods())) {
            \array_unshift($arguments, $this);

            return $this->proxy->{$name}(...$arguments);
        }

        throw new \LogicException(\sprintf(
            'Trying to call invalid method "%s" on resource of type "%s" which forwards to proxy "%s".',
            $name,
            static::class,
            \get_class($this->proxy)
        ));
    }
}
