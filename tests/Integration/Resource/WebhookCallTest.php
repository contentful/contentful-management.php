<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\Integration\Resource;

use Contentful\Management\Resource\WebhookCall;
use Contentful\Management\ResourceBuilder;
use PHPUnit\Framework\TestCase;

class WebhookCallTest extends TestCase
{
    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Class "Contentful\Management\Resource\WebhookCall" can only be instantiated as a result of an API call, manual creation is not allowed.
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
                'type' => 'WebhookCallDetails',
            ],
            'request' => [
                'method' => 'POST',
                'url' => 'https://www.example.com',
                'headers' => [],
                'body' => '{}',
            ],
            'response' => [
                'statusCode' => 200,
                'headers' => [
                    'Host' => 'www.example.com',
                    'X-Breaking-Bad-Favorite-Character' => 'Jesse Pinkman',
                ],
                'body' => '',
            ],
            'statusCode' => 200,
            'errors' => [],
            'eventType' => 'publish',
            'url' => 'https://webhooks.example.com/endpoint',
            'requestAt' => '2016-03-01T08:43:22.024Z',
            'responseAt' => '2016-03-01T08:43:22.330Z',
        ]);

        $json = '{"sys":{"type":"WebhookCallDetails"},"request":{"method":"POST","url":"https://www.example.com","headers":[],"body":"{}"},"response":{"statusCode":200,"url":"https://www.example.com","headers":{"X-Breaking-Bad-Favorite-Character":"Jesse Pinkman"},"body":""},"statusCode":200,"errors":[],"eventType":"publish","url":"https:\/\/webhooks.example.com\/endpoint","requestAt":"2016-03-01T08:43:22.024Z","responseAt":"2016-03-01T08:43:22.330Z"}';

        $this->assertJsonStringEqualsJsonString($json, json_encode($webhookCall));

        return $webhookCall;
    }

    /**
     * @param WebhookCall $webhookCall
     *
     * @depends testJsonSerialize
     * @expectedException \LogicException
     * @expectedExceptionMessage Trying to update resource object in mapper of type "Contentful\Management\Mapper\WebhookCall", but only creation from scratch is supported.
     */
    public function testInvalidUpdate(WebhookCall $webhookCall)
    {
        (new ResourceBuilder())
            ->build(['sys' => [
                'type' => 'WebhookCallDetails',
            ]], $webhookCall);
    }

    /**
     * @param WebhookCall $webhookCall
     *
     * @depends testJsonSerialize
     * @expectedException \LogicException
     * @expectedExceptionMessage Trying to convert object of class "Contentful\Management\Resource\WebhookCall" to a request body format, but operation is not supported on this class.
     */
    public function testInvalidConversionToRequestBody(WebhookCall $webhookCall)
    {
        $webhookCall->asRequestBody();
    }
}
