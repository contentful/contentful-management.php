<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\ClientExtension\Space\Environment\ContentType;

use Contentful\Core\Resource\ResourceArray;
use Contentful\Management\Query;
use Contentful\Management\Resource\ContentTypeSnapshot as ResourceClass;
use Contentful\Management\Resource\ResourceInterface;

/**
 * ContentTypeSnapshotExtension trait.
 *
 * This extension is supposed to be applied to Client class which provides a `fetchResource` method.
 *
 * @method ResourceInterface|ResourceArray fetchResource(string $class, array $parameters, Query $query = null, ResourceInterface $resource = null)
 */
trait ContentTypeSnapshotExtension
{
    /**
     * Returns a ContentTypeSnapshot resource.
     *
     * @param string $spaceId
     * @param string $environmentId
     * @param string $contentTypeId
     * @param string $snapshotId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/content-type-snapshot
     */
    public function getContentTypeSnapshot(string $spaceId, string $environmentId, string $contentTypeId, string $snapshotId): ResourceClass
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
            'environment' => $environmentId,
            'contentType' => $contentTypeId,
            'snapshot' => $snapshotId,
        ]);
    }

    /**
     * Returns a ResourceArray object which contains ContentTypeSnapshot resources.
     *
     * @param string     $spaceId
     * @param string     $environmentId
     * @param string     $contentTypeId
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/content-type-snapshots-collection
     */
    public function getContentTypeSnapshots(string $spaceId, string $environmentId, string $contentTypeId, Query $query = \null): ResourceArray
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
            'environment' => $environmentId,
            'contentType' => $contentTypeId,
        ], $query);
    }
}
