<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Resource\Behavior;

use Contentful\Management\Client;
use Contentful\Management\SystemProperties;

/**
 * ArchivableTrait.
 *
 * This trait is supposed to be applied to resources that can be archived.
 *
 * @property Client           $client
 * @property SystemProperties $sys
 */
trait ArchivableTrait
{
    /**
     * Archives the current resource.
     */
    public function archive()
    {
        return $this->client->requestWithResource($this, 'PUT', '/archived', [
            'headers' => ['X-Contentful-Version' => $this->sys->getVersion()],
        ]);
    }

    /**
     * Unarchives the current resource.
     */
    public function unarchive()
    {
        return $this->client->requestWithResource($this, 'DELETE', '/archived', [
            'headers' => ['X-Contentful-Version' => $this->sys->getVersion()],
        ]);
    }
}
