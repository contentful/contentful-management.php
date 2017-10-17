<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Proxy;

use Contentful\Management\Query;
use Contentful\Management\Resource\EntrySnapshot as ResourceClass;
use Contentful\ResourceArray;

/**
 * EntrySnapshot class.
 *
 * This class is used as a proxy for doing operations related to entry snapshots.
 */
class EntrySnapshot extends BaseProxy
{
    /**
     * {@inheritdoc}
     */
    protected function getResourceUri(array $values): string
    {
        return \rtrim(\strtr('spaces/'.$this->spaceId.'/entries/{entryId}/snapshots/{entrySnapshotId}', $values), '/');
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabledMethods(): array
    {
        return [];
    }

    /**
     * Returns an EntrySnapshot object which corresponds to the given resource ID in Contentful.
     *
     * @param string $entryId
     * @param string $entrySnapshotId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/entry-snapshot
     */
    public function get(string $entryId, string $entrySnapshotId): ResourceClass
    {
        return $this->getResource([
            '{entryId}' => $entryId,
            '{entrySnapshotId}' => $entrySnapshotId,
        ]);
    }

    /**
     * Returns a ResourceArray object containing EntrySnapshot objects.
     *
     * @param string     $entryId
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/entry-snapshots-collection
     */
    public function getAll(string $entryId, Query $query = null): ResourceArray
    {
        return $this->getResource([
            '{entryId}' => $entryId,
            '{entrySnapshotId}' => '',
        ], $query);
    }
}
