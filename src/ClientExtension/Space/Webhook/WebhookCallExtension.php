<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\ClientExtension\Space\Webhook;

use Contentful\Core\Resource\ResourceArray;
use Contentful\Management\Query;
use Contentful\Management\Resource\ResourceInterface;
use Contentful\Management\Resource\WebhookCall as ResourceClass;

/**
 * WebhookCallExtension trait.
 *
 * This extension is supposed to be applied to Client class which provides a `fetchResource` method.
 *
 * @method ResourceInterface|ResourceArray fetchResource(string $class, array $parameters, Query $query = null, ResourceInterface $resource = null)
 */
trait WebhookCallExtension
{
    /**
     * Returns a WebhookCall resource.
     *
     * @param string $spaceId
     * @param string $webhookId
     * @param string $webhookCallId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhook-calls/webhook-call-details
     */
    public function getWebhookCall(string $spaceId, string $webhookId, string $webhookCallId): ResourceClass
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
            'webhook' => $webhookId,
            'call' => $webhookCallId,
        ]);
    }

    /**
     * Returns a ResourceArray object containing WebhookCall resources.
     *
     * @param string     $spaceId
     * @param string     $webhookId
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhook-calls/webhook-call-overview
     */
    public function getWebhookCalls(string $spaceId, string $webhookId, Query $query = \null): ResourceArray
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
            'webhook' => $webhookId,
        ], $query);
    }
}
