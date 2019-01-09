<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\Behavior;

use Contentful\Management\Resource\ResourceInterface;

/**
 * CreatableInterface.
 *
 * This interface is supposed to be implemented by resources that can be created.
 */
interface CreatableInterface extends ResourceInterface
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
     * Returns an array of headers that the current resource needs to sent for being created.
     * This method is supposed to be overridden if necessary.
     *
     * @return string[]
     */
    public function getHeadersForCreation(): array;
}
