<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource;

use Contentful\Management\Resource\Webhook;
use Contentful\Management\Resource\Webhook\EqualityFilter;
use Contentful\Tests\Management\BaseTestCase;

class WebhookTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $webhook = new Webhook('Test Webhook', 'https://www.example.com/webhook');

        $this->assertSame('Test Webhook', $webhook->getName());
        $this->assertSame('https://www.example.com/webhook', $webhook->getUrl());
        $webhook->setName('Another name');
        $this->assertSame('Another name', $webhook->getName());

        $webhook->setHttpBasicUsername('my_username');
        $this->assertSame('my_username', $webhook->getHttpBasicUsername());
        $webhook->setHttpBasicPassword('k3de[@fds-54f');
        $this->assertSame('k3de[@fds-54f', $webhook->getHttpBasicPassword());

        $webhook->setHeaders([
            'X-Test-Header' => 'Test value',
            'X-Second-Test' => 'Another value',
        ]);
        $this->assertCount(2, $webhook->getHeaders());
        $this->assertFalse($webhook->hasHeader('X-Another-Header'));

        try {
            $webhook->setHeaders([
                1 => new \stdClass(),
            ]);
            $this->fail('Setting an array of headers where either keys or values are not strings should result in an exception being thrown.');
        } catch (\Throwable $exception) {
            $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
            $this->assertSame('Argument "$headers" of "Webhook::setHeaders()" must be an array where all keys and values are strings.', $exception->getMessage());
        }

        $webhook->addHeader('X-Another-Header', 'Third test value');
        $this->assertCount(3, $webhook->getHeaders());
        $this->assertTrue($webhook->hasHeader('X-Another-Header'));

        $this->assertSame('Third test value', $webhook->getHeader('X-Another-Header'));

        try {
            $webhook->getHeader('X-Not-Existing');
            $this->fail('Accessing an non-existing header should result in an exception being thrown.');
        } catch (\Throwable $exception) {
            $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
            $this->assertSame('Invalid header key "X-Not-Existing" provided.', $exception->getMessage());
        }

        $webhook->removeHeader('X-Test-Header');
        $this->assertSame([
            'X-Second-Test' => 'Another value',
            'X-Another-Header' => 'Third test value',
        ], $webhook->getHeaders());
        $this->assertSame('Third test value', $webhook->getHeader('X-Another-Header'));

        try {
            $webhook->removeHeader('X-Not-Existing');
            $this->fail('Accessing an non-existing header should result in an exception being thrown.');
        } catch (\Throwable $exception) {
            $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
            $this->assertSame('Invalid header key "X-Not-Existing" provided.', $exception->getMessage());
        }

        $webhook->setTopics(['Entry.create', '*.publish', 'invalid_key' => 'Asset.*']);
        $this->assertSame(['Entry.create', '*.publish', 'Asset.*'], $webhook->getTopics());
        $this->assertFalse($webhook->hasTopic('Entry.publish'));
        $webhook->addTopic('Entry.publish');
        $this->assertTrue($webhook->hasTopic('Entry.create'));
        $this->assertTrue($webhook->hasTopic('Entry.publish'));

        try {
            $webhook->removeTopic('Entry.archive');
            $this->fail('Accessing an non-existing topic should result in an exception being thrown.');
        } catch (\Throwable $exception) {
            $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
            $this->assertSame('Invalid topic "Entry.archive" provided.', $exception->getMessage());
        }
        $webhook->removeTopic('*.publish');
        $this->assertSame(['Entry.create', 'Asset.*', 'Entry.publish'], $webhook->getTopics());

        $filters = [new EqualityFilter('sys.environment.sys.id', 'master')];
        $webhook->setFilters($filters);
        $this->assertSame($filters, $webhook->getFilters());

        $webhook->setTransformation(['method' => 'GET']);
        $this->assertSame(['method' => 'GET'], $webhook->getTransformation());
    }

    public function testJsonSerialize()
    {
        $webhook = (new Webhook('My webhook', 'https://www.example.com/webhooks', ['Entry.create', 'ContentType.create', '*.publish', 'Asset.*']))
            ->setHttpBasicUsername('my_username')
            ->setHttpBasicPassword('k3de[@fds-54f')
            ->addHeader('X-Header-1', 'Value1')
            ->addHeader('X-Header-2', 'Value2')
            ->setFilters([new EqualityFilter('sys.environment.sys.id', 'master')])
            ->setTransformation(['method' => 'GET'])
        ;

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/webhook.json', $webhook);
    }
}
