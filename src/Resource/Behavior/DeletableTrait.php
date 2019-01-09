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

/**
 * DeletableTrait.
 *
 * This trait is supposed to be applied to resources that can be deleted.
 *
 * @property Client $client
 */
trait DeletableTrait
{
    /**
     * Deletes the current resource.
     */
    public function delete()
    {
        return $this->client->requestWithResource($this, 'DELETE');
    }
}
