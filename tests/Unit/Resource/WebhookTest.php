<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Resource;

use Contentful\Management\Resource\Webhook;
use PHPUnit\Framework\TestCase;

class WebhookTest extends TestCase
{
    public function testGetSetData()
    {
        $webhook = new Webhook('Test Webhook', 'https://www.example.com/webhook');

        $this->assertEquals('Test Webhook', $webhook->getName());
        $this->assertEquals('https://www.example.com/webhook', $webhook->getUrl());
        $webhook->setName('Another name');
        $this->assertEquals('Another name', $webhook->getName());

        $sys = $webhook->getSystemProperties();
        $this->assertEquals('WebhookDefinition', $sys->getType());

        $webhook->setHttpBasicUsername('my_username');
        $this->assertEquals('my_username', $webhook->getHttpBasicUsername());
        $webhook->setHttpBasicPassword('k3de[@fds-54f');
        $this->assertEquals('k3de[@fds-54f', $webhook->getHttpBasicPassword());

        $webhook->setHeaders([
            'X-Test-Header' => 'Test value',
            'X-Second-Test' => 'Another value',
        ]);
        $this->assertCount(2, $webhook->getHeaders());
        $this->assertFalse($webhook->hasHeader('X-Another-Header'));
        $webhook->addHeader('X-Another-Header', 'Third test value');
        $this->assertCount(3, $webhook->getHeaders());
        $this->assertTrue($webhook->hasHeader('X-Another-Header'));

        $this->assertEquals('Third test value', $webhook->getHeader('X-Another-Header'));

        try {
            $webhook->getHeader('X-Not-Existing');
            $this->fail('Accessing an non-existing header should result in an exception being thrown');
        } catch (\Throwable $e) {
            $this->assertInstanceOf(\InvalidArgumentException::class, $e);
        }

        $webhook->removeHeader('X-Test-Header');
        $this->assertEquals([
            'X-Second-Test' => 'Another value',
            'X-Another-Header' => 'Third test value',
        ], $webhook->getHeaders());
        $this->assertEquals('Third test value', $webhook->getHeader('X-Another-Header'));

        try {
            $webhook->removeHeader('X-Not-Existing');
            $this->fail('Accessing an non-existing header should result in an exception being thrown');
        } catch (\Throwable $e) {
            $this->assertInstanceOf(\InvalidArgumentException::class, $e);
        }

        $webhook->setTopics(['Entry.create', '*.publish', 'invalid_key' => 'Asset.*']);
        $this->assertEquals(['Entry.create', '*.publish', 'Asset.*'], $webhook->getTopics());
        $this->assertFalse($webhook->hasTopic('Entry.publish'));
        $webhook->addTopic('Entry.publish');
        $this->assertTrue($webhook->hasTopic('Entry.create'));
        $this->assertTrue($webhook->hasTopic('Entry.publish'));

        try {
            $webhook->removeTopic('Entry.archive');
            $this->fail('Accessing an non-existing topic should result in an exception being thrown');
        } catch (\Throwable $e) {
            $this->assertInstanceOf(\InvalidArgumentException::class, $e);
        }
        $webhook->removeTopic('*.publish');
        $this->assertEquals(['Entry.create', 'Entry.publish', 'Asset.*'], $webhook->getTopics(), '', 0, 10, true);
    }

    public function testJsonSerialize()
    {
        $json = '{"sys":{"type":"WebhookDefinition"},"name":"My webhook","url":"https://www.example.com/webhooks","topics":["Entry.create","ContentType.create","*.publish","Asset.*"],"httpBasicUsername":"my_username","httpBasicPassword":"k3de[@fds-54f","headers":[{"key":"X-Header-1","value":"Value1"},{"key":"X-Header-2","value":"Value2"}]}';

        $webhook = (new Webhook('My webhook', 'https://www.example.com/webhooks', ['Entry.create', 'ContentType.create', '*.publish', 'Asset.*']))
            ->setHttpBasicUsername('my_username')
            ->setHttpBasicPassword('k3de[@fds-54f')
            ->addHeader('X-Header-1', 'Value1')
            ->addHeader('X-Header-2', 'Value2')
        ;

        $this->assertJsonStringEqualsJsonString($json, json_encode($webhook));
    }
}
