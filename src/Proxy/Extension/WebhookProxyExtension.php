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
use Contentful\Management\Resource\WebhookCall;
use Contentful\Management\Resource\WebhookHealth;

/**
 * WebhookProxyExtension trait.
 *
 * This trait is an extension to the Webhook resource class.
 * It is built here and included as a trait to better separate concerns.
 * This trait provides shortcuts for fetching resources that belong to a webhook.
 *
 * @property Client $client
 */
trait WebhookProxyExtension
{
    /**
     * Returns the ID associated to the current space.
     *
     * @return string
     */
    abstract protected function getSpaceId();

    /**
     * Returns the ID associated to the current webhook.
     *
     * @return string
     */
    abstract protected function getWebhookId();

    /**
     * Returns a WebhookCall resource.
     *
     * @param string $callId
     *
     * @return WebhookCall
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhook-calls/webhook-call-details
     */
    public function getCall(string $callId): WebhookCall
    {
        return $this->client->getWebhookCall(
            $this->getSpaceId(),
            $this->getWebhookId(),
            $callId
        );
    }

    /**
     * Returns a ResourceArray object containing WebhookCall resources.
     *
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhook-calls/webhook-call-overview
     */
    public function getCalls(Query $query = \null): ResourceArray
    {
        return $this->client->getWebhookCalls(
            $this->getSpaceId(),
            $this->getWebhookId(),
            $query
        );
    }

    /**
     * Returns an WebhookHealth resource.
     *
     * @return WebhookHealth
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhook-calls/webhook-health
     */
    public function getHealth(): WebhookHealth
    {
        return $this->client->getWebhookHealth(
            $this->getSpaceId(),
            $this->getWebhookId()
        );
    }
}
