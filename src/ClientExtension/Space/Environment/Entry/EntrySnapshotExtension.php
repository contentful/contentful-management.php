<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\ClientExtension\Space\Environment\Entry;

use Contentful\Core\Resource\ResourceArray;
use Contentful\Management\Query;
use Contentful\Management\Resource\EntrySnapshot as ResourceClass;
use Contentful\Management\Resource\ResourceInterface;

/**
 * EntrySnapshotExtension trait.
 *
 * This extension is supposed to be applied to Client class which provides a `fetchResource` method.
 *
 * @method ResourceInterface|ResourceArray fetchResource(string $class, array $parameters, Query $query = null, ResourceInterface $resource = null)
 */
trait EntrySnapshotExtension
{
    /**
     * Returns an EntrySnapshot resource.
     *
     * @param string $spaceId
     * @param string $environmentId
     * @param string $entryId
     * @param string $snapshotId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/entry-snapshot
     */
    public function getEntrySnapshot(string $spaceId, string $environmentId, string $entryId, string $snapshotId): ResourceClass
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
            'environment' => $environmentId,
            'entry' => $entryId,
            'snapshot' => $snapshotId,
        ]);
    }

    /**
     * Returns a ResourceArray object which contains EntrySnapshot resources.
     *
     * @param string     $spaceId
     * @param string     $environmentId
     * @param string     $entryId
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/entry-snapshots-collection
     */
    public function getEntrySnapshots(string $spaceId, string $environmentId, string $entryId, Query $query = \null): ResourceArray
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
            'environment' => $environmentId,
            'entry' => $entryId,
        ], $query);
    }
}
