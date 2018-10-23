<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\Behavior;

use Contentful\Management\Resource\ResourceInterface;
use Contentful\Management\SystemProperties\VersionableSystemPropertiesInterface;

/**
 * PublishableInterface.
 *
 * This interface is supposed to be implemented by resources that can be archived.
 */
interface PublishableInterface extends ResourceInterface
{
    /**
     * @return VersionableSystemPropertiesInterface
     */
    public function getSystemProperties();
}
