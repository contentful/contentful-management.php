<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

/**
 * ResourceInterface.
 *
 * Represents a resource managed by Contentful.
 */
interface ResourceInterface extends \JsonSerializable
{
    /**
     * Returns the resource's system properties,
     * defined in the object `sys` in Contentful's responses.
     *
     * @return SystemProperties
     */
    public function getSystemProperties(): SystemProperties;
}
