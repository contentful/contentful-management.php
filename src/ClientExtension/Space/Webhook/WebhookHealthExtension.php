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
use Contentful\Management\Resource\WebhookHealth as ResourceClass;

/**
 * WebhookHealthExtension trait.
 *
 * This extension is supposed to be applied to Client class which provides a `fetchResource` method.
 *
 * @method ResourceInterface|ResourceArray fetchResource(string $class, array $parameters, Query $query = null, ResourceInterface $resource = null)
 */
trait WebhookHealthExtension
{
    /**
     * Returns an WebhookHealth resource.
     *
     * @param string $spaceId
     * @param string $webhookId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhook-calls/webhook-health
     */
    public function getWebhookHealth(string $spaceId, string $webhookId): ResourceClass
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
            'webhook' => $webhookId,
        ]);
    }
}
