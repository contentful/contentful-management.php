<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
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
use Contentful\Management\Resource\DeliveryApiKey;
use Contentful\Management\Resource\EditorInterface;
use Contentful\Management\Resource\Entry;
use Contentful\Management\Resource\EntrySnapshot;
use Contentful\Management\Resource\Extension;
use Contentful\Management\Resource\Locale;
use Contentful\Management\Resource\PreviewApiKey;
use Contentful\Management\Resource\Role;
use Contentful\Management\Resource\SpaceMembership;
use Contentful\Management\Resource\Upload;
use Contentful\Management\Resource\Webhook;
use Contentful\Management\Resource\WebhookCall;
use Contentful\Management\Resource\WebhookHealth;

/**
 * SpaceProxyExtension trait.
 *
 * This trait is an extension to the Space resource class.
 * It is built here and included as a trait to better separate concerns.
 * This trait provides shortcuts for fetching resources that belong to a space.
 *
 * @property Client $client
 */
trait SpaceProxyExtension
{
    /**
     * Returns the ID associated to the current space.
     *
     * @return string
     */
    abstract protected function getSpaceId();

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
    public function getAssets(Query $query = null): ResourceArray
    {
        return $this->client->getAssets(
            $this->getSpaceId(),
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
    public function getContentTypes(Query $query = null): ResourceArray
    {
        return $this->client->getContentTypes(
            $this->getSpaceId(),
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
    public function getContentTypeSnapshots(string $contentTypeId, Query $query = null): ResourceArray
    {
        return $this->client->getContentTypeSnapshots(
            $this->getSpaceId(),
            $contentTypeId,
            $query
        );
    }

    /**
     * Returns a DeliveryApiKey resource.
     *
     * @param string $deliveryApiKeyId
     *
     * @return DeliveryApiKey
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/api-keys
     */
    public function getDeliveryApiKey(string $deliveryApiKeyId): DeliveryApiKey
    {
        return $this->client->getDeliveryApiKey(
            $this->getSpaceId(),
            $deliveryApiKeyId
        );
    }

    /**
     * Returns a ResourceArray object containing DeliveryApiKey objects.
     *
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/api-keys/api-keys-collection
     */
    public function getDeliveryApiKeys(Query $query = null): ResourceArray
    {
        return $this->client->getDeliveryApiKeys(
            $this->getSpaceId(),
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
    public function getEntries(Query $query = null): ResourceArray
    {
        return $this->client->getEntries(
            $this->getSpaceId(),
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
    public function getEntrySnapshots(string $entryId, Query $query = null): ResourceArray
    {
        return $this->client->getEntrySnapshots(
            $this->getSpaceId(),
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
            $this->getSpaceId()
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
            $this->getSpaceId()
        );
    }

    /**
     * Returns a PreviewApiKey resource.
     *
     * @param string $previewApiKeyId
     *
     * @return PreviewApiKey
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/api-keys
     */
    public function getPreviewApiKey(string $previewApiKeyId): PreviewApiKey
    {
        return $this->client->getPreviewApiKey(
            $this->getSpaceId(),
            $previewApiKeyId
        );
    }

    /**
     * Returns a ResourceArray object containing PreviewApiKey resources.
     *
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/api-keys/api-keys-collection
     */
    public function getPreviewApiKeys(Query $query = null): ResourceArray
    {
        return $this->client->getPreviewApiKeys(
            $this->getSpaceId(),
            $query
        );
    }

    /**
     * Returns a Role resource.
     *
     * @param string $roleId
     *
     * @return Role
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/roles/role
     */
    public function getRole(string $roleId): Role
    {
        return $this->client->getRole(
            $this->getSpaceId(),
            $roleId
        );
    }

    /**
     * Returns a ResourceArray object containing Role resources.
     *
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/roles/roles-collection
     */
    public function getRoles(Query $query = null): ResourceArray
    {
        return $this->client->getRoles(
            $this->getSpaceId(),
            $query
        );
    }

    /**
     * Returns a SpaceMembership resource.
     *
     * @param string $spaceMembershipId
     *
     * @return SpaceMembership
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/space-memberships/space-membership
     */
    public function getSpaceMembership(string $spaceMembershipId): SpaceMembership
    {
        return $this->client->getSpaceMembership(
            $this->getSpaceId(),
            $spaceMembershipId
        );
    }

    /**
     * Returns a ResourceArray object containing SpaceMembership resources.
     *
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/space-memberships
     */
    public function getSpaceMemberships(Query $query = null): ResourceArray
    {
        return $this->client->getSpaceMemberships(
            $this->getSpaceId(),
            $query
        );
    }

    /**
     * Returns an Upload resource.
     *
     * @param string $uploadId
     *
     * @return Upload
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/uploads/retrieving-an-upload
     */
    public function getUpload(string $uploadId): Upload
    {
        return $this->client->getUpload(
            $this->getSpaceId(),
            $uploadId
        );
    }

    /**
     * Returns a Webhook resource.
     *
     * @param string $webhookId
     *
     * @return Webhook
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhooks/webhook
     */
    public function getWebhook(string $webhookId): Webhook
    {
        return $this->client->getWebhook(
            $this->getSpaceId(),
            $webhookId
        );
    }

    /**
     * Returns a ResourceArray object containing Webhook resources.
     *
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhooks/webhooks-collection
     */
    public function getWebhooks(Query $query = null): ResourceArray
    {
        return $this->client->getWebhooks(
            $this->getSpaceId(),
            $query
        );
    }

    /**
     * Returns a WebhookCall resource.
     *
     * @param string $webhookId
     * @param string $callId
     *
     * @return WebhookCall
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhook-calls/webhook-call-details
     */
    public function getWebhookCall(string $webhookId, string $callId): WebhookCall
    {
        return $this->client->getWebhookCall(
            $this->getSpaceId(),
            $webhookId,
            $callId
        );
    }

    /**
     * Returns a ResourceArray object containing WebhookCall resources.
     *
     * @param string     $webhookId
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhook-calls/webhook-call-overview
     */
    public function getWebhookCalls(string $webhookId, Query $query = null): ResourceArray
    {
        return $this->client->getWebhookCalls(
            $this->getSpaceId(),
            $webhookId,
            $query
        );
    }

    /**
     * Returns an WebhookHealth resource.
     *
     * @param string $webhookId
     *
     * @return WebhookHealth
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhook-calls/webhook-health
     */
    public function getWebhookHealth(string $webhookId): WebhookHealth
    {
        return $this->client->getWebhookHealth(
            $this->getSpaceId(),
            $webhookId
        );
    }
}
