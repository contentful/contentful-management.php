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
use Contentful\Management\Resource\WebhookCall as ResourceClass;
use Contentful\ResourceArray;

/**
 * WebhookCall class.
 */
class WebhookCall extends BaseProxy
{
    /**
     * {@inheritdoc}
     */
    public function getResourceUri(array $values): string
    {
        return \rtrim(\strtr('spaces/'.$this->spaceId.'/webhooks/{webhookId}/calls/{webhookCallId}', $values), '/');
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabledMethods(): array
    {
        return [];
    }

    /**
     * Returns an Asset object which corresponds to the given resource ID in Contentful.
     *
     * @param string $webhookId
     * @param string $webhookCallId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhook-calls/webhook-call-details
     */
    public function get(string $webhookId, string $webhookCallId): ResourceClass
    {
        return $this->getResource([
            '{webhookId}' => $webhookId,
            '{webhookCallId}' => $webhookCallId,
        ]);
    }

    /**
     * Returns a ResourceArray object containing Asset objects.
     *
     * @param string     $webhookId
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhook-calls/webhook-call-overview
     */
    public function getAll(string $webhookId, Query $query = null): ResourceArray
    {
        return $this->getResource([
            '{webhookId}' => $webhookId,
            '{webhookCallId}' => '',
        ], $query);
    }
}
