<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Management\Resource\Behavior\Creatable;
use Contentful\Management\Resource\Behavior\Deletable;
use Contentful\Management\Resource\Behavior\Updatable;
use function GuzzleHttp\json_encode;

/**
 * Locale class.
 *
 * This class represents a resource with type "Locale" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/locales
 * @see https://www.contentful.com/developers/docs/concepts/locales/
 */
class Locale extends BaseResource implements Creatable, Updatable, Deletable
{
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
    protected $contentDeliveryApi = true;

    /**
     * @var bool
     */
    protected $contentManagementApi = true;

    /**
     * @var bool
     */
    protected $default = false;

    /**
     * @var bool
     */
    protected $optional = false;

    /**
     * Locale constructor.
     *
     * @param string      $name
     * @param string      $code
     * @param string|null $fallbackCode
     */
    public function __construct(string $name, string $code, string $fallbackCode = null)
    {
        parent::__construct('Locale');
        $this->name = $name;
        $this->code = $code;
        $this->fallbackCode = $fallbackCode;
    }

    /**
     * Returns an array to be used by "json_encode" to serialize objects of this class.
     *
     * @return array
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

        return json_encode((object) $body, JSON_UNESCAPED_UNICODE);
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
    public function setFallbackCode(string $fallbackCode = null)
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
