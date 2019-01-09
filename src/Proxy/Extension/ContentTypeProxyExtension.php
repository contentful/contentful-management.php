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
use Contentful\Management\Resource\ContentTypeSnapshot;
use Contentful\Management\Resource\EditorInterface;

/**
 * ContentTypeProxyExtension trait.
 *
 * This trait is an extension to the ContentType resource class.
 * It is built here and included as a trait to better separate concerns.
 * This trait provides shortcuts for fetching resources that belong to a content type.
 *
 * @property Client $client
 */
trait ContentTypeProxyExtension
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
     * Returns the ID associated to the current content type.
     *
     * @return string
     */
    abstract protected function getContentTypeId();

    /**
     * Returns a ContentTypeSnapshot resource.
     *
     * @param string $snapshotId
     *
     * @return ContentTypeSnapshot
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/content-type-snapshot
     */
    public function getSnapshot(string $snapshotId): ContentTypeSnapshot
    {
        return $this->client->getContentTypeSnapshot(
            $this->getSpaceId(),
            $this->getEnvironmentId(),
            $this->getContentTypeId(),
            $snapshotId
        );
    }

    /**
     * Returns a ResourceArray object which contains ContentTypeSnapshot resources.
     *
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/content-type-snapshots-collection
     */
    public function getSnapshots(Query $query = \null): ResourceArray
    {
        return $this->client->getContentTypeSnapshots(
            $this->getSpaceId(),
            $this->getEnvironmentId(),
            $this->getContentTypeId(),
            $query
        );
    }

    /**
     * Returns an EditorInterface resource.
     *
     * @return EditorInterface
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/editor-interface
     */
    public function getEditorInterface(): EditorInterface
    {
        return $this->client->getEditorInterface(
            $this->getSpaceId(),
            $this->getEnvironmentId(),
            $this->getContentTypeId()
        );
    }
}
