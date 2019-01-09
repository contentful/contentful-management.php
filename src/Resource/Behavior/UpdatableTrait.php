<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\Behavior;

use Contentful\Management\Client;
use Contentful\Management\SystemProperties\VersionableSystemPropertiesInterface;

/**
 * UpdatableTrait.
 *
 * This trait is supposed to be applied to resources that can be updated.
 *
 * @property Client $client
 *
 * @method VersionableSystemPropertiesInterface getSystemProperties()
 */
trait UpdatableTrait
{
    /**
     * Returns the resource in the form of request body.
     * This can differ from regular serialization, as some fields
     * may not be present in the request payload.
     *
     * @return mixed
     */
    abstract public function asRequestBody();

    /**
     * Updates the current resource.
     */
    public function update()
    {
        return $this->client->requestWithResource($this, 'PUT', '', [
            'headers' => ['X-Contentful-Version' => $this->getSystemProperties()->getVersion()],
            'body' => $this->asRequestBody(),
        ]);
    }
}
