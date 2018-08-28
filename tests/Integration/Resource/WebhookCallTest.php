<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Integration\Resource;

use Contentful\Management\Resource\WebhookCall;
use Contentful\Management\ResourceBuilder;
use Contentful\Tests\Management\BaseTestCase;

class WebhookCallTest extends BaseTestCase
{
    /**
     * @expectedException \Error
     */
    public function testInvalidCreation()
    {
        new WebhookCall();
    }

    /**
     * This test is meaningless.
     * It's only here because private, empty constructors are not correctly picked by code coverage.
     */
    public function testConstructor()
    {
        $class = new \ReflectionClass(WebhookCall::class);
        $object = $class->newInstanceWithoutConstructor();
        $constructor = $class->getConstructor();
        $constructor->setAccessible(true);
        $constructor->invoke($object);

        $this->markTestAsPassed();
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

        $this->assertJsonFixtureEqualsJsonObject('Integration/Resource/webhook_call.json', $webhookCall);

        return $webhookCall;
    }

    /**
     * @param WebhookCall $webhookCall
     *
     * @depends testJsonSerialize
     * @expectedException        \LogicException
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
     * @expectedException        \LogicException
     * @expectedExceptionMessage Trying to convert object of class "Contentful\Management\Resource\WebhookCall" to a request body format, but operation is not supported on this class.
     */
    public function testInvalidConversionToRequestBody(WebhookCall $webhookCall)
    {
        $webhookCall->asRequestBody();
    }
}
