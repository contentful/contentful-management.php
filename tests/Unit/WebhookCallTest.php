<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\Unit;

use Contentful\Management\ResourceBuilder;

class WebhookCallTest extends \PHPUnit_Framework_TestCase
{
    public function testJsonSerialize()
    {
        $builder = new ResourceBuilder();

        $data = [
            'sys' => [
                'type' => 'WebhookCallOverview',
            ],
            'statusCode' => 200,
            'errors' => [],
            'eventType' => 'publish',
            'url' => 'https://webhooks.example.com/endpoint',
            'requestAt' => '2016-03-01T08:43:22.024Z',
            'responseAt' => '2016-03-01T08:43:22.330Z',
        ];

        $webhookCallDetails = $builder->buildObjectsFromRawData($data);

        $json = '{"sys":{"type":"WebhookCallOverview"},"statusCode":200,"errors":[],"eventType":"publish","url":"https:\/\/webhooks.example.com\/endpoint","requestAt":"2016-03-01T08:43:22.024Z","responseAt":"2016-03-01T08:43:22.330Z"}';

        $this->assertJsonStringEqualsJsonString($json, json_encode($webhookCallDetails));
    }
}
