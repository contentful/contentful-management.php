<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Integration\Resource;

use Contentful\Management\ResourceBuilder;
use Contentful\Management\Resource\WebhookCallDetails;
use PHPUnit\Framework\TestCase;

class WebhookCallDetailsTest extends TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testInvalidCreation()
    {
        new WebhookCallDetails();
    }

    /**
     * @return WebhookCallDetails
     */
    public function testJsonSerialize(): WebhookCallDetails
    {
        $webhookCallDetails = (new ResourceBuilder())->build([
            'sys' => [
                'type' => 'WebhookCallDetails',
            ],
            'request' => [
                'url' => 'https://webhooks.example.com/endpoint',
                'method' => 'POST',
                'headers' => [
                    'X-Contentful-Topic' => 'ContentManagement.Entry.publish',
                    'Content-Type' => 'application/vnd.contentful.management.v1+json',
                ],
                'body' => '{}',
            ],
            'response' => [
                'url' => 'https://webhooks.example.com/endpoint',
                'headers' => [
                    'Content-Type' => 'text/html; charset=utf-8',
                    'Content-Length' => '2',
                ],
                'body' => 'ok',
                'statusCode' => 200,
            ],
            'statusCode' => 200,
            'errors' => [],
            'eventType' => 'publish',
            'url' => 'https://webhooks.example.com/endpoint',
            'requestAt' => '2016-03-01T08:43:22.024Z',
            'responseAt' => '2016-03-01T08:43:22.330Z',
        ]);

        $json = '{"sys":{"type":"WebhookCallDetails"},"request":{"url":"https:\/\/webhooks.example.com\/endpoint","method":"POST","headers":{"X-Contentful-Topic":"ContentManagement.Entry.publish","Content-Type":"application\/vnd.contentful.management.v1+json"},"body":"{}"},"response":{"url":"https:\/\/webhooks.example.com\/endpoint","headers":{"Content-Type":"text\/html; charset=utf-8","Content-Length":"2"},"body":"ok","statusCode":200},"statusCode":200,"errors":[],"eventType":"publish","url":"https:\/\/webhooks.example.com\/endpoint","requestAt":"2016-03-01T08:43:22.024Z","responseAt":"2016-03-01T08:43:22.330Z"}';

        $this->assertJsonStringEqualsJsonString($json, json_encode($webhookCallDetails));

        return $webhookCallDetails;
    }

    /**
     * @param WebhookCallDetails $webhookCallDetails
     *
     * @depends testJsonSerialize
     * @expectedException \LogicException
     */
    public function testInvalidUpdate(WebhookCallDetails $webhookCallDetails)
    {
        (new ResourceBuilder())
            ->build(['sys' => [
                'type' => 'WebhookCallDetails',
            ]], $webhookCallDetails);
    }
}
