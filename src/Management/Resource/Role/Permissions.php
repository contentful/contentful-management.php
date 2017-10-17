<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Resource\Role;

/**
 * Permissions class.
 */
class Permissions implements \JsonSerializable
{
    /**
     * @var string|null
     */
    private $contentDelivery;

    /**
     * @var string|null
     */
    private $contentModel;

    /**
     * @var string|null
     */
    private $settings;

    /**
     * @return string|null
     */
    public function getContentDelivery()
    {
        return $this->contentDelivery;
    }

    /**
     * @param string|null $access Either null, or one of "read", "manage", "all"
     *
     * @return static
     */
    public function setContentDelivery(string $access = null)
    {
        if ($access !== null && !\in_array($access, ['read', 'manage', 'all'])) {
            throw new \InvalidArgumentException(sprintf(
                'Parameter $access in Permissions::setContentDelivery() must be either null or one of "read", "manage", "all", "%s" given.',
                $access
            ));
        }

        $this->contentDelivery = $access;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContentModel()
    {
        return $this->contentModel;
    }

    /**
     * @param string|null $access Either null, or one of "read", "manage", "all"
     *
     * @return static
     */
    public function setContentModel(string $access = null)
    {
        if ($access !== null && !\in_array($access, ['read', 'manage', 'all'])) {
            throw new \InvalidArgumentException(sprintf(
                'Parameter $access in Permissions::setContentModel() must be either null or one of "read", "manage", "all", "%s" given.',
                $access
            ));
        }

        $this->contentModel = $access;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param string|null $access Either null, or one of "manage", "all"
     *
     * @return static
     */
    public function setSettings(string $access = null)
    {
        if ($access !== null && !\in_array($access, ['manage', 'all'])) {
            throw new \InvalidArgumentException(sprintf(
                'Parameter $access in Permissions::setSettings() must be either null or one of "manage", "all", "%s" given.',
                $access
            ));
        }

        $this->settings = $access;

        return $this;
    }

    /**
     * Returns an array to be used by "json_encode" to serialize objects of this class.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $permissions = [];

        if ($this->contentDelivery !== null) {
            $permissions['ContentDelivery'] = $this->contentDelivery == 'all'
                ? 'all'
                : ($this->contentDelivery == 'manage'
                    ? ['read', 'manage']
                    : ['read']);
        }
        if ($this->contentModel !== null) {
            $permissions['ContentModel'] = $this->contentModel == 'all'
                ? 'all'
                : ($this->contentModel == 'manage'
                    ? ['read', 'manage']
                    : ['read']);
        }
        if ($this->settings !== null) {
            $permissions['Settings'] = $this->settings == 'all'
                ? 'all'
                : ['manage'];
        }

        return $permissions;
    }
}
