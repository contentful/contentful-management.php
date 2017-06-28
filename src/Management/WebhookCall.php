<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

use Contentful\DateHelper;

class WebhookCall implements SpaceScopedResourceInterface
{
    /**
     * @var SystemProperties
     */
    private $sys;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var string|null
     */
    private $error;

    /**
     * @var string
     */
    private $eventType;

    /**
     * @var string
     */
    private $url;

    /**
     * @var \DateTimeImmutable
     */
    private $requestAt;

    /**
     * @var \DateTimeImmutable
     */
    private $responseAt;

    public function __construct()
    {
        $this->sys = SystemProperties::withType('WebhookCallOverview');
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
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return string|null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return string
     */
    public function getEventType(): string
    {
        return $this->eventType;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getRequestAt(): \DateTimeImmutable
    {
        return $this->requestAt;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getResponseAt(): \DateTimeImmutable
    {
        return $this->responseAt;
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
            'statusCode' => $this->statusCode,
            'errors' => $this->error ? [$this->error] : [],
            'eventType' => $this->eventType,
            'url' => $this->url,
            'requestAt' => DateHelper::formatForJson($this->requestAt),
            'responseAt' => DateHelper::formatForJson($this->responseAt),
        ];
    }
}
