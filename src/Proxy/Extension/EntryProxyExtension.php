<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Proxy\Extension;

use Contentful\Core\Resource\ResourceArray;
use Contentful\Management\Client;
use Contentful\Management\Query;
use Contentful\Management\Resource\EntrySnapshot;

/**
 * EntryProxyExtension trait.
 *
 * This trait is an extension to the Entry resource class.
 * It is built here and included as a trait to better separate concerns.
 * This trait provides shortcuts for fetching resources that belong to an entry.
 *
 * @property Client $client
 */
trait EntryProxyExtension
{
    /**
     * Returns the ID associated to the current space.
     *
     * @return string
     */
    abstract protected function getSpaceId();

    /**
     * Returns the ID associated to the current environment.
     *
     * @return string
     */
    abstract protected function getEnvironmentId();

    /**
     * Returns the ID associated to the current entry.
     *
     * @return string
     */
    abstract protected function getEntryId();

    /**
     * Returns a EntrySnapshot resource.
     *
     * @param string $snapshotId
     *
     * @return EntrySnapshot
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/entry-snapshot
     */
    public function getSnapshot(string $snapshotId): EntrySnapshot
    {
        return $this->client->getEntrySnapshot(
            $this->getSpaceId(),
            $this->getEnvironmentId(),
            $this->getEntryId(),
            $snapshotId
        );
    }

    /**
     * Returns a ResourceArray object which contains EntrySnapshot resources.
     *
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/entry-snapshots-collection
     */
    public function getSnapshots(Query $query = \null): ResourceArray
    {
        return $this->client->getEntrySnapshots(
            $this->getSpaceId(),
            $this->getEnvironmentId(),
            $this->getEntryId(),
            $query
        );
    }
}
