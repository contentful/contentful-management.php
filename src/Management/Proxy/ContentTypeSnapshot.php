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
use Contentful\Management\Resource\ContentTypeSnapshot as ResourceClass;
use Contentful\ResourceArray;

/**
 * ContentTypeSnapshot class.
 *
 * This class is used as a proxy for doing operations related to content type snapshots.
 */
class ContentTypeSnapshot extends BaseProxy
{
    /**
     * {@inheritdoc}
     */
    protected function getResourceUri(array $values): string
    {
        return \rtrim(\strtr('spaces/'.$this->spaceId.'/content_types/{contentTypeId}/snapshots/{contentTypeSnapshotId}', $values), '/');
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabledMethods(): array
    {
        return [];
    }

    /**
     * Returns a ContentTypeSnapshot object which corresponds to the given resource ID in Contentful.
     *
     * @param string $contentTypeId
     * @param string $contentTypeSnapshotId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/content-type-snapshot
     */
    public function get(string $contentTypeId, string $contentTypeSnapshotId): ResourceClass
    {
        return $this->getResource([
            '{contentTypeId}' => $contentTypeId,
            '{contentTypeSnapshotId}' => $contentTypeSnapshotId,
        ]);
    }

    /**
     * Returns a ResourceArray object containing ContentTypeSnapshot objects.
     *
     * @param string     $contentTypeId
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/content-type-snapshots-collection
     */
    public function getAll(string $contentTypeId, Query $query = null): ResourceArray
    {
        return $this->getResource([
            '{contentTypeId}' => $contentTypeId,
            '{contentTypeSnapshotId}' => '',
        ], $query);
    }
}
