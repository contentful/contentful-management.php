<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource;

use Contentful\DateHelper;
use Contentful\Management\SystemProperties;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * WebhookCallDetails class.
 *
 * This class represents a resource with type "WebhookCallDetails" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhook-calls/webhook-call-details
 */
class WebhookCallDetails implements SpaceScopedResourceInterface
{
    /**
     * @var SystemProperties
     */
    private $sys;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

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

    /**
     * WebhookCallDetails constructor.
     */
    public function __construct()
    {
        $this->sys = SystemProperties::withType('WebhookCallDetails');
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
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
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
        // The request object automatically adds a `Host` header, which we don't need
        $headers = $this->formatPsr7Headers($this->request->getHeaders());
        unset($headers['Host']);
        $request = [
            'url' => (string) $this->request->getUri(),
            'method' => $this->request->getMethod(),
            'headers' => $headers,
            'body' => (string) $this->request->getBody(),
        ];

        $response = [
            'url' => (string) $this->request->getUri(),
            'statusCode' => $this->response->getStatusCode(),
            'headers' => $this->formatPsr7Headers($this->response->getHeaders()),
            'body' => (string) $this->response->getBody(),
        ];

        return (object) [
            'sys' => $this->sys,
            'request' => $request,
            'response' => $response,
            'statusCode' => $this->statusCode,
            'errors' => $this->error ? [$this->error] : [],
            'eventType' => $this->eventType,
            'url' => $this->url,
            'requestAt' => DateHelper::formatForJson($this->requestAt),
            'responseAt' => DateHelper::formatForJson($this->responseAt),
        ];
    }

    /**
     * PSR-7 Headers can contain multiple values for every key.
     * We simplify management by only defining one.
     *
     * @param array $headers
     *
     * @return array
     */
    private function formatPsr7Headers(array $headers): array
    {
        $returnHeaders = [];
        foreach ($headers as $key => $values) {
            $returnHeaders[$key] = $values[0];
        }

        return $returnHeaders;
    }
}
