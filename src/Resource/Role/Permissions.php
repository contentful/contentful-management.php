<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
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
    public function setContentDelivery(string $access = \null)
    {
        if (\null !== $access && !\in_array($access, ['read', 'manage', 'all'], \true)) {
            throw new \InvalidArgumentException(\sprintf(
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
    public function setContentModel(string $access = \null)
    {
        if (\null !== $access && !\in_array($access, ['read', 'manage', 'all'], \true)) {
            throw new \InvalidArgumentException(\sprintf(
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
    public function setSettings(string $access = \null)
    {
        if (\null !== $access && !\in_array($access, ['manage', 'all'], \true)) {
            throw new \InvalidArgumentException(\sprintf(
                'Parameter $access in Permissions::setSettings() must be either null or one of "manage", "all", "%s" given.',
                $access
            ));
        }

        $this->settings = $access;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        $permissions = [];

        if (\null !== $this->contentDelivery) {
            $permissions['ContentDelivery'] = 'all' === $this->contentDelivery
                ? 'all'
                : ('manage' === $this->contentDelivery
                    ? ['read', 'manage']
                    : ['read']);
        }
        if (\null !== $this->contentModel) {
            $permissions['ContentModel'] = 'all' === $this->contentModel
                ? 'all'
                : ('manage' === $this->contentModel
                    ? ['read', 'manage']
                    : ['read']);
        }
        if (\null !== $this->settings) {
            $permissions['Settings'] = 'all' === $this->settings
                ? 'all'
                : ['manage'];
        }

        return $permissions;
    }
}
