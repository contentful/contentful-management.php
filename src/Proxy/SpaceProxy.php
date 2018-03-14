<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Proxy;

use Contentful\Core\Api\Link;
use Contentful\Core\Resource\ResourceArray;
use Contentful\Management\Client;
use Contentful\Management\Query;
use Contentful\Management\Resource\Behavior\CreatableInterface;
use Contentful\Management\Resource\ContentTypeSnapshot;
use Contentful\Management\Resource\EditorInterface;
use Contentful\Management\Resource\EntrySnapshot;
use Contentful\Management\Resource\ResourceInterface;
use Contentful\Management\Resource\WebhookCall;
use Contentful\Management\Resource\WebhookHealth;

/**
 * SpaceProxy class.
 *
 * This class works as a lazy reference to a space resource.
 * You can use it for most space-related needs, such as fetching entries, assets and content types,
 * or creating them (attaching them to this space).
 *
 * To access this class, you can use the convenience method found in a client object.
 *
 * ``` php
 * $space = $client->getSpaceProxy($spaceId);
 * ```
 *
 * The methods provided are very similar to the getX() methods you will find in an actual space resource object.
 * The main difference is that when fetching a space resource, you will actually call the API,
 * whereas with this proxy, you're just holding a reference to a certain space.
 * This is the reason why in most situations you should prefer using a SpaceProxy rather than a space resource.
 *
 * ``` php
 * // Only the entries query will be made
 * $space = $client->getSpaceProxy($spaceId);
 * $entries = $space->getEntries();
 *
 * // Two queries will be made
 * $space = $client->getSpace($spaceId);
 * $entries = $space->getEntries();
 * ```
 */
class SpaceProxy
{
    use Extension\SpaceProxyExtension;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $spaceId;

    /**
     * @param Client $client
     * @param string $spaceId
     */
    public function __construct(Client $client, string $spaceId)
    {
        $this->client = $client;
        $this->spaceId = $spaceId;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSpaceId()
    {
        return $this->spaceId;
    }

    /**
     * Persist a new resource in Contentful.
     * This is a convenience method which just forwards to Client::create(),
     * but setting the `space` key to the current space ID in the parameters array.
     *
     * @param CreatableInterface         $resource
     * @param ResourceInterface|string[] $parameters
     * @param string                     $id
     *
     * @see \Contentful\Management\Client::create()
     */
    public function create(CreatableInterface $resource, $parameters = [], string $id = '')
    {
        if (\is_array($parameters)) {
            $parameters['space'] = $this->spaceId;
        }

        $this->client->create($resource, $parameters, $id);
    }

    /**
     * Resolves a Contentful link scoped to the current space.
     *
     * @param Link $link
     *
     * @return ResourceInterface
     */
    public function resolveLink(Link $link): ResourceInterface
    {
        $linkMap = [
            'Asset' => 'Asset',
            'ContentType' => 'ContentType',
            'Entry' => 'Entry',
            'PreviewApiKey' => 'PreviewApiKey',
            'Role' => 'Role',
            'Space' => 'Space',
            'Upload' => 'Upload',
            'WebhookDefinition' => 'Webhook',
        ];

        if (!isset($linkMap[$link->getLinkType()])) {
            throw new \InvalidArgumentException(\sprintf(
                'Unexpected system type "%s" while trying to resolve a Link.',
                $link->getLinkType()
            ));
        }

        $resource = 'Contentful\\Management\\Resource\\'.$linkMap[$link->getLinkType()];
        $parameter = \lcfirst($linkMap[$link->getLinkType()]);

        return $this->client->fetchResource($resource, [
            'space' => $this->spaceId,
            $parameter => $link->getId(),
        ]);
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
