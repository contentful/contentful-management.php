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
use Contentful\Management\SystemProperties;

/**
 * ResourceInterface.
 *
 * Represents a resource managed by Contentful.
 */
interface ResourceInterface extends \JsonSerializable
{
    /**
     * Returns the resource's system properties,
     * defined in the object "sys" in Contentful's responses.
     *
     * @return SystemProperties
     */
    public function getSystemProperties(): SystemProperties;

    /**
     * Creates a Link representation of the current resource.
     *
     * @return Link
     */
    public function asLink(): Link;
}
