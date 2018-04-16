<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Resource\Behavior;

use Contentful\Management\Client;
use Contentful\Management\SystemProperties;

/**
 * UpdatableTrait.
 *
 * This trait is supposed to be applied to resources that can be updated.
 *
 * @property Client           $client
 * @property SystemProperties $sys
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
            'headers' => ['X-Contentful-Version' => $this->sys->getVersion()],
            'body' => $this->asRequestBody(),
        ]);
    }
}
