<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource;

/**
 * WebhookHealth class.
 *
 * This class represents a resource with type "Webhook" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhook-calls/webhook-health
 */
class WebhookHealth extends BaseResource implements SpaceScopedResourceInterface
{
    /**
     * @var int
     */
    protected $total = 0;

    /**
     * @var int
     */
    protected $healthy = 0;

    /**
     * WebhookHealth constructor.
     */
    public function __construct()
    {
        parent::__construct('Webhook');
    }

    /**
     * {@inheritdoc}
     */
    public function getResourceUriPart(): string
    {
        return 'webhooks';
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return int
     */
    public function getHealthy(): int
    {
        return $this->healthy;
    }

    /**
     * Returns an array to be used by `json_encode` to serialize objects of this class.
     *
     * @return array
     *
     * @see http://php.net/manual/en/jsonserializable.jsonserialize.php JsonSerializable::jsonSerialize
     */
    public function jsonSerialize(): array
    {
        return [
            'sys' => $this->sys,
            'calls' => [
                'total' => $this->total,
                'healthy' => $this->healthy,
            ],
        ];
    }
}
