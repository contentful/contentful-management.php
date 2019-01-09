<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Management\Resource\Behavior\CreatableInterface;
use Contentful\Management\Resource\Behavior\DeletableTrait;
use Contentful\Management\Resource\Behavior\UpdatableTrait;
use Contentful\Management\SystemProperties\Locale as SystemProperties;
use function GuzzleHttp\json_encode as guzzle_json_encode;

/**
 * Locale class.
 *
 * This class represents a resource with type "Locale" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/locales
 * @see https://www.contentful.com/developers/docs/concepts/locales/
 */
class Locale extends BaseResource implements CreatableInterface
{
    use DeletableTrait,
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
     * @var string
     */
    protected $code;

    /**
     * @var string|null
     */
    protected $fallbackCode;

    /**
     * @var bool
     */
    protected $contentDeliveryApi = \true;

    /**
     * @var bool
     */
    protected $contentManagementApi = \true;

    /**
     * @var bool
     */
    protected $default = \false;

    /**
     * @var bool
     */
    protected $optional = \false;

    /**
     * Locale constructor.
     *
     * @param string      $name
     * @param string      $code
     * @param string|null $fallbackCode
     */
    public function __construct(string $name, string $code, string $fallbackCode = \null)
    {
        $this->name = $name;
        $this->code = $code;
        $this->fallbackCode = $fallbackCode;
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
            'code' => $this->code,
            'fallbackCode' => $this->fallbackCode,
            'contentDeliveryApi' => $this->contentDeliveryApi,
            'contentManagementApi' => $this->contentManagementApi,
            'default' => $this->default,
            'optional' => $this->optional,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function asRequestBody(): string
    {
        $body = $this->jsonSerialize();

        unset($body['sys']);
        // The property 'default' has to be omitted for the API to work.
        unset($body['default']);

        return guzzle_json_encode((object) $body, \JSON_UNESCAPED_UNICODE);
    }

    /**
     * {@inheritdoc}
     */
    public function asUriParameters(): array
    {
        return [
            'space' => $this->sys->getSpace()->getId(),
            'environment' => $this->sys->getEnvironment()->getId(),
            'locale' => $this->sys->getId(),
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

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return static
     */
    public function setCode(string $code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFallbackCode()
    {
        return $this->fallbackCode;
    }

    /**
     * @param string|null $fallbackCode
     *
     * @return static
     */
    public function setFallbackCode(string $fallbackCode = \null)
    {
        $this->fallbackCode = $fallbackCode;

        return $this;
    }

    /**
     * @return bool
     */
    public function isContentDeliveryApi(): bool
    {
        return $this->contentDeliveryApi;
    }

    /**
     * @param bool $contentDeliveryApi
     *
     * @return static
     */
    public function setContentDeliveryApi(bool $contentDeliveryApi)
    {
        $this->contentDeliveryApi = $contentDeliveryApi;

        return $this;
    }

    /**
     * @return bool
     */
    public function isContentManagementApi(): bool
    {
        return $this->contentManagementApi;
    }

    /**
     * @param bool $contentManagementApi
     *
     * @return static
     */
    public function setContentManagementApi(bool $contentManagementApi)
    {
        $this->contentManagementApi = $contentManagementApi;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->default;
    }

    /**
     * @return bool
     */
    public function isOptional(): bool
    {
        return $this->optional;
    }

    /**
     * @param bool $optional
     *
     * @return static
     */
    public function setOptional(bool $optional)
    {
        $this->optional = $optional;

        return $this;
    }
}
