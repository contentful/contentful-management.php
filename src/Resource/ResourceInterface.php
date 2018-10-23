<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Core\Resource\ResourceInterface as CoreResourceInterface;
use Contentful\Management\Proxy\ProxyInterface;

interface ResourceInterface extends CoreResourceInterface
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
     * Returns an associative array where keys are the name of the fragments
     * in a URI, and the values are the corresponding IDs.
     *
     * @return string[]
     */
    public function asUriParameters(): array;

    /**
     * Sets the current proxy object instance.
     * This is done automatically when performing API calls,
     * so it shouldn't be used manually.
     *
     * @param ProxyInterface $proxy
     */
    public function setProxy(ProxyInterface $proxy);
}
