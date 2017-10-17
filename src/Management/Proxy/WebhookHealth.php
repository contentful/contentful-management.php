<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Proxy;

use Contentful\Management\Resource\WebhookHealth as ResourceClass;

/**
 * WebhookHealth class.
 */
class WebhookHealth extends BaseProxy
{
    /**
     * {@inheritdoc}
     */
    public function getResourceUri(array $values): string
    {
        return \rtrim(\strtr('spaces/'.$this->spaceId.'/webhooks/{webhookId}/health', $values), '/');
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
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhook-calls/webhook-health
     */
    public function get(string $webhookId): ResourceClass
    {
        return $this->getResource([
            '{webhookId}' => $webhookId,
        ]);
    }
}
