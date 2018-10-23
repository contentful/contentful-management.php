<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties;

use Contentful\Core\Api\Link;
use Contentful\Core\Resource\SystemPropertiesInterface;

interface SpaceScopedSystemPropertiesInterface extends SystemPropertiesInterface
{
    /**
     * @return Link
     */
    public function getSpace(): Link;
}
