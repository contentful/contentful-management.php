<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties;

use Contentful\Core\Resource\SystemPropertiesInterface;

interface VersionableSystemPropertiesInterface extends SystemPropertiesInterface
{
    /**
     * @return int
     */
    public function getVersion(): int;
}
