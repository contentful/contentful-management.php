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
use Contentful\Management\Resource\WebhookCall;
use PHPUnit\Framework\TestCase;

class WebhookCallTest extends TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testInvalidCreation()
    {
        new WebhookCall();
    }

    /**
     * @return WebhookCall
     */
    public function testJsonSerialize(): WebhookCall
    {
        $webhookCall = (new ResourceBuilder())->build([
            'sys' => [
                'type' => 'WebhookCallOverview',
            ],
            'statusCode' => 200,
            'errors' => [],
            'eventType' => 'publish',
            'url' => 'https://webhooks.example.com/endpoint',
            'requestAt' => '2016-03-01T08:43:22.024Z',
            'responseAt' => '2016-03-01T08:43:22.330Z',
        ]);

        $json = '{"sys":{"type":"WebhookCallOverview"},"statusCode":200,"errors":[],"eventType":"publish","url":"https:\/\/webhooks.example.com\/endpoint","requestAt":"2016-03-01T08:43:22.024Z","responseAt":"2016-03-01T08:43:22.330Z"}';

        $this->assertJsonStringEqualsJsonString($json, json_encode($webhookCall));

        return $webhookCall;
    }

    /**
     * @param WebhookCall $webhookCall
     *
     * @depends testJsonSerialize
     * @expectedException \LogicException
     */
    public function testInvalidUpdate(WebhookCall $webhookCall)
    {
        (new ResourceBuilder())
            ->build(['sys' => [
                'type' => 'WebhookCallOverview',
            ]], $webhookCall);
    }
}
