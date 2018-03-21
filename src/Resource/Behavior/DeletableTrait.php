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
 * DeletableTrait.
 *
 * This trait is supposed to be applied to resources that can be deleted.
 *
 * @property Client           $client
 * @property SystemProperties $sys
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
