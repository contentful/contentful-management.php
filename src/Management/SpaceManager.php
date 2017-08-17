<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

use function GuzzleHttp\json_encode;
use Contentful\Exception\SpaceMismatchException;
use Contentful\Management\Behavior\Archivable;
use Contentful\Management\Behavior\Creatable;
use Contentful\Management\Behavior\Deletable;
use Contentful\Management\Behavior\Publishable;
use Contentful\Management\Behavior\Updatable;
use Contentful\Management\Resource\Asset;
use Contentful\Management\Resource\ContentType;
use Contentful\Management\Resource\PreviewApiKey;
use Contentful\Management\Resource\DeliveryApiKey;
use Contentful\Management\Resource\SpaceMembership;
use Contentful\Management\Resource\ContentTypeSnapshot;
use Contentful\Management\Resource\Entry;
use Contentful\Management\Resource\EntrySnapshot;
use Contentful\Management\Resource\Locale;
use Contentful\Management\Resource\ResourceInterface;
use Contentful\Management\Resource\Role;
use Contentful\Management\Resource\Space;
use Contentful\Management\Resource\Upload;
use Contentful\Management\Resource\Webhook;
use Contentful\Management\Resource\WebhookCallDetails;
use Contentful\Management\Resource\WebhookHealth;
use Contentful\ResourceArray;

/**
 * SpaceManager class.
 *
 * This class is responsible for executing operations on a space level,
 * such as creating and deleting of resources, as well as retrieval of specific resource types.
 */
class SpaceManager
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var ResourceBuilder
     */
    private $builder;

    /**
     * @var string
     */
    private $spaceId;

    /**
     * SpaceManager constructor.
     *
     * @param Client          $client
     * @param ResourceBuilder $builder
     * @param string          $spaceId
     */
    public function __construct(Client $client, ResourceBuilder $builder, $spaceId)
    {
        $this->client = $client;
        $this->builder = $builder;
        $this->spaceId = $spaceId;
    }

    /**
     * Checks that the given resource is compatible with the currently-managed space.
     *
     * @param ResourceInterface $resource
     *
     * @throws SpaceMismatchException
     */
    public function checkSpaceMismatch(ResourceInterface $resource)
    {
        $sys = $resource->getSystemProperties();
        $resourceSpaceId = $resource instanceof Space
            ? $sys->getId()
            : $sys->getSpace()->getId();

        if ($resourceSpaceId !== $this->spaceId) {
            throw new SpaceMismatchException('Can\'t perform action on space '.$resource->getSystemProperties()->getSpace()->getId().' with a SpaceManager responsible for '.$this->spaceId.'.');
        }
    }

    /**
     * Publishes a resource.
     *
     * @param Publishable $resource
     *
     * @see Publishable
     */
    public function publish(Publishable $resource)
    {
        $this->checkSpaceMismatch($resource);

        $sys = $resource->getSystemProperties();
        $uriParts = [
            'spaces',
            $this->spaceId,
            $resource->getResourceUriPart(),
            $sys->getId(),
            'published',
        ];

        $response = $this->client->request('PUT', implode('/', $uriParts), [
            'additionalHeaders' => ['X-Contentful-Version' => $sys->getVersion()],
        ]);

        $this->builder->build($response, $resource);
    }

    /**
     * Unpublishes a resource.
     *
     * @param Publishable $resource
     *
     * @see Publishable
     */
    public function unpublish(Publishable $resource)
    {
        $this->checkSpaceMismatch($resource);

        $sys = $resource->getSystemProperties();
        $uriParts = [
            'spaces',
            $this->spaceId,
            $resource->getResourceUriPart(),
            $sys->getId(),
            'published',
        ];

        $response = $this->client->request('DELETE', implode('/', $uriParts), [
            'additionalHeaders' => ['X-Contentful-Version' => $sys->getVersion()],
        ]);

        $this->builder->build($response, $resource);
    }

    /**
     * Archives a resource.
     *
     * @param Archivable $resource
     *
     * @see Archivable
     */
    public function archive(Archivable $resource)
    {
        $this->checkSpaceMismatch($resource);

        $sys = $resource->getSystemProperties();
        $uriParts = [
            'spaces',
            $this->spaceId,
            $resource->getResourceUriPart(),
            $sys->getId(),
            'archived',
        ];

        $response = $this->client->request('PUT', implode('/', $uriParts), [
            'additionalHeaders' => ['X-Contentful-Version' => $sys->getVersion()],
        ]);

        $this->builder->build($response, $resource);
    }

    /**
     * Unarchives a resource.
     *
     * @param Archivable $resource
     *
     * @see Archivable
     */
    public function unarchive(Archivable $resource)
    {
        $this->checkSpaceMismatch($resource);

        $sys = $resource->getSystemProperties();
        $uriParts = [
            'spaces',
            $this->spaceId,
            $resource->getResourceUriPart(),
            $sys->getId(),
            'archived',
        ];

        $response = $this->client->request('DELETE', implode('/', $uriParts), [
            'additionalHeaders' => ['X-Contentful-Version' => $sys->getVersion()],
        ]);

        $this->builder->build($response, $resource);
    }

    /**
     * Deletes a resource.
     *
     * @param Deletable $resource
     *
     * @see Deletable
     */
    public function delete(Deletable $resource)
    {
        $this->checkSpaceMismatch($resource);

        $sys = $resource->getSystemProperties();
        $uriParts = [
            'spaces',
            $this->spaceId,
            $resource->getResourceUriPart(),
            $sys->getId(),
        ];

        $options = [];
        if ($resource instanceof Upload) {
            $options['baseUri'] = Client::URI_UPLOAD;
        }

        $this->client->request('DELETE', implode('/', $uriParts), $options);
    }

    /**
     * Updates a resource.
     *
     * @param Updatable $resource
     *
     * @see Updatable
     */
    public function update(Updatable $resource)
    {
        $this->checkSpaceMismatch($resource);

        $sys = $resource->getSystemProperties();
        $body = json_encode($this->client->prepareObjectForApi($resource), JSON_UNESCAPED_UNICODE);
        $uriParts = [
            'spaces',
            $this->spaceId,
            $resource->getResourceUriPart(),
            $sys->getId(),
        ];

        $response = $this->client->request('PUT', implode('/', $uriParts), [
            'additionalHeaders' => ['X-Contentful-Version' => $sys->getVersion()],
            'body' => $body,
        ]);

        $this->builder->build($response, $resource);
    }

    /**
     * Creates a resource.
     *
     * @param Creatable   $resource
     * @param string|null $id
     *
     * @see Creatable
     */
    public function create(Creatable $resource, string $id = null)
    {
        $options = [
            'body' => json_encode($this->client->prepareObjectForApi($resource), JSON_UNESCAPED_UNICODE),
            'additionalHeaders' => [],
        ];

        if ($resource instanceof Entry) {
            $options['additionalHeaders']['X-Contentful-Content-Type'] = $resource->getSystemProperties()->getContentType()->getId();
        }

        if ($resource instanceof Upload) {
            $options['baseUri'] = Client::URI_UPLOAD;
            $options['additionalHeaders']['Content-Type'] = 'application/octet-stream';
            $options['body'] = $resource->getBody();
        }

        $uriParts = [
            'spaces',
            $this->spaceId,
            $resource->getResourceUriPart(),
        ];

        if ($id !== null) {
            $uriParts[] = $id;
        }

        $method = $id === null ? 'POST' : 'PUT';
        $response = $this->client->request($method, implode('/', $uriParts), $options);

        $this->builder->build($response, $resource);
    }

    /**
     * @param string $assetId
     *
     * @return Asset
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/assets/asset
     */
    public function getAsset(string $assetId): Asset
    {
        return $this->client->get('spaces/'.$this->spaceId.'/assets/'.$assetId);
    }

    /**
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/assets/published-assets-collection
     */
    public function getAssets(Query $query = null): ResourceArray
    {
        return $this->client->get('spaces/'.$this->spaceId.'/assets', $query);
    }

    /**
     * @param string $deliveryApiKeyId
     *
     * @return DeliveryApiKey
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/api-keys
     */
    public function getDeliveryApiKey(string $deliveryApiKeyId): DeliveryApiKey
    {
        return $this->client->get('spaces/'.$this->spaceId.'/api_keys/'.$deliveryApiKeyId);
    }

    /**
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/api-keys
     */
    public function getDeliveryApiKeys(Query $query = null): ResourceArray
    {
        return $this->client->get('spaces/'.$this->spaceId.'/api_keys', $query);
    }

    /**
     * @param string $previewApiKeyId
     *
     * @return PreviewApiKey
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/api-keys
     */
    public function getPreviewApiKey(string $previewApiKeyId): PreviewApiKey
    {
        return $this->client->get('spaces/'.$this->spaceId.'/preview_api_keys/'.$previewApiKeyId);
    }

    /**
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/api-keys
     */
    public function getPreviewApiKeys(Query $query = null): ResourceArray
    {
        return $this->client->get('spaces/'.$this->spaceId.'/preview_api_keys', $query);
    }

    /**
     * @param Asset  $asset
     * @param string $locale
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/assets/asset-processing
     */
    public function processAsset(Asset $asset, string $locale)
    {
        $sys = $asset->getSystemProperties();
        $additionalHeaders = ['X-Contentful-Version' => $sys->getVersion()];

        $this->client->request('PUT', 'spaces/'.$this->spaceId.'/assets/'.$sys->getId().'/files/'.$locale.'/process', [
            'additionalHeaders' => $additionalHeaders,
        ]);

        // Fetch the Asset because it's not returned from the above API call
        $response = $this->client->request('GET', 'spaces/'.$this->spaceId.'/assets/'.$sys->getId());

        $this->builder->build($response, $asset);
    }

    /**
     * @param string $uploadId
     *
     * @return Upload
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/uploads/retrieving-an-upload
     */
    public function getUpload(string $uploadId): Upload
    {
        return $this->client->get('spaces/'.$this->spaceId.'/uploads/'.$uploadId, null, ['baseUri' => Client::URI_UPLOAD]);
    }

    /**
     * @param string $localeId
     *
     * @return Locale
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/locales/locale
     */
    public function getLocale(string $localeId): Locale
    {
        return $this->client->get('spaces/'.$this->spaceId.'/locales/'.$localeId);
    }

    /**
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/locales/locale-collection
     */
    public function getLocales(): ResourceArray
    {
        return $this->client->get('spaces/'.$this->spaceId.'/locales');
    }

    /**
     * @param string $contentTypeId [ption]
     *
     * @return ContentType
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/content-types/content-type
     */
    public function getContentType(string $contentTypeId): ContentType
    {
        return $this->client->get('spaces/'.$this->spaceId.'/content_types/'.$contentTypeId);
    }

    /**
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/content-types/content-type-collection
     */
    public function getContentTypes(Query $query = null): ResourceArray
    {
        return $this->client->get('spaces/'.$this->spaceId.'/content_types', $query);
    }

    /**
     * @param string $contentTypeId
     *
     * @return ContentType
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/content-types/activated-content-type-collection
     */
    public function getPublishedContentType(string $contentTypeId): ContentType
    {
        return $this->client->get('spaces/'.$this->spaceId.'/public/content_types/'.$contentTypeId);
    }

    /**
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/content-types/activated-content-type-collection
     */
    public function getPublishedContentTypes(Query $query = null): ResourceArray
    {
        return $this->client->get('spaces/'.$this->spaceId.'/public/content_types', $query);
    }

    /**
     * @param string $entryId
     *
     * @return Entry
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/entries/entry
     */
    public function getEntry(string $entryId): Entry
    {
        return $this->client->get('spaces/'.$this->spaceId.'/entries/'.$entryId);
    }

    /**
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/entries/entries-collection
     */
    public function getEntries(Query $query = null): ResourceArray
    {
        return $this->client->get('spaces/'.$this->spaceId.'/entries', $query);
    }

    /**
     * @param string $entryId
     * @param string $snapshotId
     *
     * @return EntrySnapshot
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/entry-snapshot
     */
    public function getEntrySnapshot(string $entryId, string $snapshotId): EntrySnapshot
    {
        return $this->client->get('spaces/'.$this->spaceId.'/entries/'.$entryId.'/snapshots/'.$snapshotId);
    }

    /**
     * @param string     $entryId
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/entry-snapshots-collection
     */
    public function getEntrySnapshots(string $entryId, Query $query = null): ResourceArray
    {
        return $this->client->get('spaces/'.$this->spaceId.'/entries/'.$entryId.'/snapshots', $query);
    }

    /**
     * @param string $contentTypeId
     * @param string $snapshotId
     *
     * @return ContentTypeSnapshot
     */
    public function getContentTypeSnapshot(string $contentTypeId, string $snapshotId): ContentTypeSnapshot
    {
        return $this->client->get('spaces/'.$this->spaceId.'/content_types/'.$contentTypeId.'/snapshots/'.$snapshotId);
    }

    /**
     * @param string     $contentTypeId
     * @param Query|null $query
     *
     * @return ResourceArray
     */
    public function getContentTypeSnapshots(string $contentTypeId, Query $query = null): ResourceArray
    {
        return $this->client->get('spaces/'.$this->spaceId.'/content_types/'.$contentTypeId.'/snapshots', $query);
    }

    /**
     * @param string $roleId
     *
     * @return Role
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/roles/role
     */
    public function getRole(string $roleId): Role
    {
        return $this->client->get('spaces/'.$this->spaceId.'/roles/'.$roleId);
    }

    /**
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/roles/roles-collection
     */
    public function getRoles(Query $query = null): ResourceArray
    {
        return $this->client->get('spaces/'.$this->spaceId.'/roles', $query);
    }

    /**
     * @param string $spaceMembershipId
     *
     * @return SpaceMembership
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/space-memberships/space-membership
     */
    public function getSpaceMembership(string $spaceMembershipId): SpaceMembership
    {
        return $this->client->get('spaces/'.$this->spaceId.'/space_memberships/'.$spaceMembershipId);
    }

    /**
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/space-memberships
     */
    public function getSpaceMemberships(Query $query = null): ResourceArray
    {
        return $this->client->get('spaces/'.$this->spaceId.'/space_memberships', $query);
    }

    /**
     * @param string $webhookId
     *
     * @return Webhook
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhooks/webhook
     */
    public function getWebhook(string $webhookId): Webhook
    {
        return $this->client->get('spaces/'.$this->spaceId.'/webhook_definitions/'.$webhookId);
    }

    /**
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhooks/webhooks-collection
     */
    public function getWebhooks(Query $query = null): ResourceArray
    {
        return $this->client->get('spaces/'.$this->spaceId.'/webhook_definitions', $query);
    }

    /**
     * @param string $webhookId
     *
     * @return WebhookHealth
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhook-calls/webhook-health
     */
    public function getWebhookHealth(string $webhookId): WebhookHealth
    {
        return $this->client->get('spaces/'.$this->spaceId.'/webhooks/'.$webhookId.'/health');
    }

    /**
     * @param string     $webhookId
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhook-calls/webhook-call-overview
     */
    public function getWebhookCalls(string $webhookId, Query $query = null): ResourceArray
    {
        return $this->client->get('spaces/'.$this->spaceId.'/webhooks/'.$webhookId.'/calls', $query);
    }

    /**
     * @param string $webhookId
     * @param string $webhookCallId
     *
     * @return WebhookCallDetails
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhook-calls/webhook-call-details
     */
    public function getWebhookCallDetails(string $webhookId, string $webhookCallId)
    {
        return $this->client->get('spaces/'.$this->spaceId.'/webhooks/'.$webhookId.'/calls/'.$webhookCallId);
    }
}
