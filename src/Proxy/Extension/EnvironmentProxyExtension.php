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
use Contentful\Management\Resource\Asset;
use Contentful\Management\Resource\ContentType;
use Contentful\Management\Resource\ContentTypeSnapshot;
use Contentful\Management\Resource\EditorInterface;
use Contentful\Management\Resource\Entry;
use Contentful\Management\Resource\EntrySnapshot;
use Contentful\Management\Resource\Extension;
use Contentful\Management\Resource\Locale;

/**
 * EnvironmentProxyExtension trait.
 *
 * This trait is an extension to the Environment resource class.
 * It is built here and included as a trait to better separate concerns.
 * This trait provides shortcuts for fetching resources that belong to an environment.
 *
 * @property Client $client
 */
trait EnvironmentProxyExtension
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
     * Returns an Asset resource.
     *
     * @param string $assetId
     *
     * @return Asset
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/assets/asset
     */
    public function getAsset(string $assetId): Asset
    {
        return $this->client->getAsset(
            $this->getSpaceId(),
            $this->getEnvironmentId(),
            $assetId
        );
    }

    /**
     * Returns a ResourceArray object which contains Asset resources.
     *
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/assets/assets-collection
     */
    public function getAssets(Query $query = \null): ResourceArray
    {
        return $this->client->getAssets(
            $this->getSpaceId(),
            $this->getEnvironmentId(),
            $query
        );
    }

    /**
     * Returns a ContentType resource.
     *
     * @param string $contentTypeId
     *
     * @return ContentType
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/content-types/content-type
     */
    public function getContentType(string $contentTypeId): ContentType
    {
        return $this->client->getContentType(
            $this->getSpaceId(),
            $this->getEnvironmentId(),
            $contentTypeId
        );
    }

    /**
     * Returns a ResourceArray object which contains ContentType resources.
     *
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/content-types/content-type-collection
     */
    public function getContentTypes(Query $query = \null): ResourceArray
    {
        return $this->client->getContentTypes(
            $this->getSpaceId(),
            $this->getEnvironmentId(),
            $query
        );
    }

    /**
     * Returns a published ContentType resource.
     *
     * @param string $contentTypeId
     *
     * @return ContentType
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/content-types/activated-content-type-collection
     */
    public function getPublishedContentType(string $contentTypeId): ContentType
    {
        return $this->client->getPublishedContentType(
            $this->getSpaceId(),
            $this->getEnvironmentId(),
            $contentTypeId
        );
    }

    /**
     * Returns a ResourceArray object which contains published ContentType resources.
     *
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/content-types/activated-content-type-collection
     */
    public function getPublishedContentTypes(Query $query = \null): ResourceArray
    {
        return $this->client->getPublishedContentTypes(
            $this->getSpaceId(),
            $this->getEnvironmentId(),
            $query
        );
    }

    /**
     * Returns a ContentTypeSnapshot resource.
     *
     * @param string $contentTypeId
     * @param string $snapshotId
     *
     * @return ContentTypeSnapshot
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/content-type-snapshot
     */
    public function getContentTypeSnapshot(string $contentTypeId, string $snapshotId): ContentTypeSnapshot
    {
        return $this->client->getContentTypeSnapshot(
            $this->getSpaceId(),
            $this->getEnvironmentId(),
            $contentTypeId,
            $snapshotId
        );
    }

    /**
     * Returns a ResourceArray object which contains ContentTypeSnapshot resources.
     *
     * @param string     $contentTypeId
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/content-type-snapshots-collection
     */
    public function getContentTypeSnapshots(string $contentTypeId, Query $query = \null): ResourceArray
    {
        return $this->client->getContentTypeSnapshots(
            $this->getSpaceId(),
            $this->getEnvironmentId(),
            $contentTypeId,
            $query
        );
    }

    /**
     * Returns an EditorInterface resource.
     *
     * @param string $contentTypeId
     *
     * @return EditorInterface
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/editor-interface
     */
    public function getEditorInterface(string $contentTypeId): EditorInterface
    {
        return $this->client->getEditorInterface(
            $this->getSpaceId(),
            $this->getEnvironmentId(),
            $contentTypeId
        );
    }

    /**
     * Returns an Entry resource.
     *
     * @param string $entryId
     *
     * @return Entry
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/entries/entry
     */
    public function getEntry(string $entryId): Entry
    {
        return $this->client->getEntry(
            $this->getSpaceId(),
            $this->getEnvironmentId(),
            $entryId
        );
    }

    /**
     * Returns a ResourceArray object which contains Entry resources.
     *
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/entries/entries-collection
     */
    public function getEntries(Query $query = \null): ResourceArray
    {
        return $this->client->getEntries(
            $this->getSpaceId(),
            $this->getEnvironmentId(),
            $query
        );
    }

    /**
     * Returns a EntrySnapshot resource.
     *
     * @param string $entryId
     * @param string $snapshotId
     *
     * @return EntrySnapshot
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/entry-snapshot
     */
    public function getEntrySnapshot(string $entryId, string $snapshotId): EntrySnapshot
    {
        return $this->client->getEntrySnapshot(
            $this->getSpaceId(),
            $this->getEnvironmentId(),
            $entryId,
            $snapshotId
        );
    }

    /**
     * Returns a ResourceArray object which contains EntrySnapshot resources.
     *
     * @param string     $entryId
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/entry-snapshots-collection
     */
    public function getEntrySnapshots(string $entryId, Query $query = \null): ResourceArray
    {
        return $this->client->getEntrySnapshots(
            $this->getSpaceId(),
            $this->getEnvironmentId(),
            $entryId,
            $query
        );
    }

    /**
     * Returns an Extension resource.
     *
     * @param string $extensionId
     *
     * @return Extension
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/ui-extensions/extension
     */
    public function getExtension(string $extensionId): Extension
    {
        return $this->client->getExtension(
            $this->getSpaceId(),
            $this->getEnvironmentId(),
            $extensionId
        );
    }

    /**
     * Returns a ResourceArray object containing Extension resources.
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/ui-extensions/extensions-collection
     */
    public function getExtensions(): ResourceArray
    {
        return $this->client->getExtensions(
            $this->getSpaceId(),
            $this->getEnvironmentId()
        );
    }

    /**
     * Returns a Locale resource.
     *
     * @param string $localeId
     *
     * @return Locale
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/locales/locale
     */
    public function getLocale(string $localeId): Locale
    {
        return $this->client->getLocale(
            $this->getSpaceId(),
            $this->getEnvironmentId(),
            $localeId
        );
    }

    /**
     * Returns a ResourceArray object containing Locale resources.
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/locales/locale-collection
     */
    public function getLocales(): ResourceArray
    {
        return $this->client->getLocales(
            $this->getSpaceId(),
            $this->getEnvironmentId()
        );
    }
}
