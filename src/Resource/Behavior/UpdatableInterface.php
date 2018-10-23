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
 * UpdatableInterface.
 *
 * This interface is supposed to be implemented by resources that can be archived.
 */
interface UpdatableInterface extends ResourceInterface
{
    /**
     * Returns the resource in the form of request body.
     * This can differ from regular serialization, as some fields
     * may not be present in the request payload.
     *
     * @return mixed
     */
    public function asRequestBody();

    /**
     * @return VersionableSystemPropertiesInterface
     */
    public function getSystemProperties();
}
