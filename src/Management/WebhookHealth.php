<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

/**
 * WebhookHealth class.
 *
 * This class represents a resource with type "Webhook" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhook-calls/webhook-health
 */
class WebhookHealth implements SpaceScopedResourceInterface
{
    /**
     * @var SystemProperties
     */
    private $sys;

    /**
     * @var int
     */
    private $total = 0;

    /**
     * @var int
     */
    private $healthy = 0;

    /**
     * WebhookHealth constructor.
     */
    public function __construct()
    {
        $this->sys = SystemProperties::withType('Webhook');
    }

    /**
     * {@inheritDoc}
     */
    public function getSystemProperties(): SystemProperties
    {
        return $this->sys;
    }

    /**
     * {@inheritDoc}
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
     * Returns an object to be used by `json_encode` to serialize objects of this class.
     *
     * @return object
     *
     * @see http://php.net/manual/en/jsonserializable.jsonserialize.php JsonSerializable::jsonSerialize
     */
    public function jsonSerialize()
    {
        return (object) [
            'sys' => $this->sys,
            'calls' => [
                'total' => $this->total,
                'healthy' => $this->healthy,
            ],
        ];
    }
}
