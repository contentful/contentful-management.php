<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource\Role;

/**
 * Permissions class.
 */
class Permissions implements \JsonSerializable
{
    /**
     * Either "all" or an array including a subset of ["read", "manage"].
     *
     * @var string|string[]
     */
    private $contentDelivery = [];

    /**
     * Either "all" or an array including a subset of ["read", "manage"].
     *
     * @var string|string[]
     */
    private $contentModel = [];

    /**
     * Either "all" or an array including a subset of ["manage"].
     *
     * @var string|string[]
     */
    private $settings = [];

    /**
     * @return string|string[] Either "all" or an array including a subset of ["read", "manage"]
     */
    public function getContentDelivery()
    {
        return $this->contentDelivery;
    }

    /**
     * @param string|string[] $values Either "all" or an array including a subset of ["read", "manage"]
     *
     * @return $this
     */
    public function setContentDelivery($values)
    {
        if (
            (!is_string($values) && !is_array($values)) ||
            (is_string($values) && $values !== 'all') ||
            (is_array($values) && array_diff($values, ['read', 'manage']))
        ) {
            throw new \InvalidArgumentException(
                'Argument "$values" in "Permissions::setContentDelivery()" must be either a string "all", or an array containing a subset of ["read", "manage"].'
            );
        }

        $this->contentDelivery = $values;

        return $this;
    }

    /**
     * @return string|array Either "all" or an array including a subset of ["read", "manage"]
     */
    public function getContentModel()
    {
        return $this->contentModel;
    }

    /**
     * @param string|array $values Either "all" or an array including a subset of ["read", "manage"]
     *
     * @return $this
     */
    public function setContentModel($values)
    {
        if (
            (!is_string($values) && !is_array($values)) ||
            (is_string($values) && $values !== 'all') ||
            (is_array($values) && array_diff($values, ['read', 'manage']))
        ) {
            throw new \InvalidArgumentException(
                'Argument "$values" in "Permissions::setContentModel()" must be either a string "all", or an array containing a subset of ["read", "manage"].'
            );
        }

        $this->contentModel = $values;

        return $this;
    }

    /**
     * @return string|array Either "all" or an array including a subset of ["read", "manage"]
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param string|array $values Either "all" or an array including a subset of ["manage"]
     *
     * @return $this
     */
    public function setSettings($values)
    {
        if (
            (!is_string($values) && !is_array($values)) ||
            (is_string($values) && $values !== 'all') ||
            (is_array($values) && array_diff($values, ['manage']))
        ) {
            throw new \InvalidArgumentException(
                'Argument "$values" in "Permissions::setSettings()" must be either a string "all", or an array containing a subset of ["manage"].'
            );
        }

        $this->settings = $values;

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
        $permission = [];

        if ($this->contentDelivery !== null) {
            $permission['ContentDelivery'] = $this->contentDelivery;
        }
        if ($this->contentModel !== null) {
            $permission['ContentModel'] = $this->contentModel;
        }
        if ($this->settings !== null) {
            $permission['Settings'] = $this->settings;
        }

        return $permission;
    }
}
