<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource;

use Contentful\Management\Behavior\Creatable;
use Contentful\Management\Behavior\Deletable;
use Contentful\Management\Behavior\Updatable;
use Contentful\Management\SystemProperties;

/**
 * Locale class.
 *
 * This class represents a resource with type "Locale" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/locales
 * @see https://www.contentful.com/developers/docs/concepts/locales/
 */
class Locale implements SpaceScopedResourceInterface, Deletable, Updatable, Creatable
{
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
        $this->sys = SystemProperties::withType('Locale');
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
    public function getResourceUriPart(): string
    {
        return 'locales';
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
     * @return $this;\
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
     * @return $this;
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    public function setOptional(bool $optional)
    {
        $this->optional = $optional;

        return $this;
    }

    /**
     * Returns an array to be used by `json_encode` to serialize objects of this class.
     *
     * @return array
     *
     * @see http://php.net/manual/en/jsonserializable.jsonserialize.php JsonSerializable::jsonSerialize
     */
    public function jsonSerialize(): array
    {
        // The property 'default' has to be omitted for the API to work.
        return [
            'sys' => $this->sys,
            'name' => $this->name,
            'code' => $this->code,
            'fallbackCode' => $this->fallbackCode,
            'contentDeliveryApi' => $this->contentDeliveryApi,
            'contentManagementApi' => $this->contentManagementApi,
            'optional' => $this->optional,
        ];
    }
}
