<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties;

use Contentful\Core\Resource\SystemPropertiesInterface;

interface VersionableSystemPropertiesInterface extends SystemPropertiesInterface
{
    public function getVersion(): int;
}
