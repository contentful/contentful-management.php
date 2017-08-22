<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * WebhookCallDetails class.
 *
 * This class represents a resource with type "WebhookCallDetails" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhook-calls/webhook-call-details
 */
class WebhookCallDetails extends WebhookCall
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

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
     * Returns an array to be used by `json_encode` to serialize objects of this class.
     *
     * @return array
     *
     * @see http://php.net/manual/en/jsonserializable.jsonserialize.php JsonSerializable::jsonSerialize
     */
    public function jsonSerialize(): array
    {
        $webhookCall = parent::jsonSerialize();

        $webhookCall['request'] = [
            'url' => (string) $this->request->getUri(),
            'method' => $this->request->getMethod(),
            'headers' => $this->formatPsr7Headers($this->request->getHeaders()),
            'body' => (string) $this->request->getBody(),
        ];

        $webhookCall['response'] = [
            'url' => (string) $this->request->getUri(),
            'statusCode' => $this->response->getStatusCode(),
            'headers' => $this->formatPsr7Headers($this->response->getHeaders()),
            'body' => (string) $this->response->getBody(),
        ];

        return $webhookCall;
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
            // The request object automatically adds a `Host` header, which we don't need
            if ($key == 'Host') {
                continue;
            }

            $returnHeaders[$key] = $values[0];
        }

        return $returnHeaders;
    }
}
