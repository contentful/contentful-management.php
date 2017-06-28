<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

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

    public function __construct()
    {
        $this->sys = SystemProperties::withType('Webhook');
    }

    /**
     * @return SystemProperties
     */
    public function getSystemProperties(): SystemProperties
    {
        return $this->sys;
    }

    public function getResourceUrlPart(): string
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
