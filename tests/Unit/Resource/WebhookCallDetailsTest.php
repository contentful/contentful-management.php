<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\Unit\Resource;

use Contentful\Management\ResourceBuilder;
use PHPUnit\Framework\TestCase;

class WebhookCallDetailsTest extends TestCase
{
    public function testJsonSerialize()
    {
        $builder = new ResourceBuilder();

        $data = [
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
        ];

        $webhookCallDetails = $builder->buildObjectsFromRawData($data);

        $json = '{"sys":{"type":"WebhookCallDetails"},"request":{"url":"https:\/\/webhooks.example.com\/endpoint","method":"POST","headers":{"X-Contentful-Topic":"ContentManagement.Entry.publish","Content-Type":"application\/vnd.contentful.management.v1+json"},"body":"{}"},"response":{"url":"https:\/\/webhooks.example.com\/endpoint","headers":{"Content-Type":"text\/html; charset=utf-8","Content-Length":"2"},"body":"ok","statusCode":200},"statusCode":200,"errors":[],"eventType":"publish","url":"https:\/\/webhooks.example.com\/endpoint","requestAt":"2016-03-01T08:43:22.024Z","responseAt":"2016-03-01T08:43:22.330Z"}';

        $this->assertJsonStringEqualsJsonString($json, json_encode($webhookCallDetails));
    }
}
