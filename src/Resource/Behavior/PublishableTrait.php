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
 * PublishableTrait.
 *
 * This trait is supposed to be applied to resources that can be published.
 *
 * @property Client $client
 *
 * @method VersionableSystemPropertiesInterface getSystemProperties()
 */
trait PublishableTrait
{
    /**
     * Publishes the current resource.
     */
    public function publish()
    {
        return $this->client->requestWithResource($this, 'PUT', '/published', [
            'headers' => ['X-Contentful-Version' => $this->getSystemProperties()->getVersion()],
        ]);
    }

    /**
     * Unpublishes the current resource.
     */
    public function unpublish()
    {
        return $this->client->requestWithResource($this, 'DELETE', '/published', [
            'headers' => ['X-Contentful-Version' => $this->getSystemProperties()->getVersion()],
        ]);
    }
}
