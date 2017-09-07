<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\E2E\Management;

use Contentful\Link;
use Contentful\Management\ApiDateTime;
use Contentful\Management\Query;
use Contentful\Management\Resource\Entry;
use Contentful\Management\Resource\Webhook;
use Contentful\Management\Resource\WebhookCall;
use Contentful\Tests\End2EndTestCase;

class WebhookTest extends End2EndTestCase
{
    /**
     * @vcr e2e_webhook_get_one.json
     */
    public function testGetWebhook()
    {
        $client = $this->getReadWriteClient();

        $webhook = $client->webhook->get('3tilCowN1lI1rDCe9vhK0C');

        $this->assertEquals(new Link('3tilCowN1lI1rDCe9vhK0C', 'WebhookDefinition'), $webhook->asLink());
        $this->assertEquals('Default Webhook', $webhook->getName());
        $this->assertEquals('https://www.example.com/default-webhook', $webhook->getUrl());
        $this->assertEquals('default_username', $webhook->getHttpBasicUsername());
        $this->assertEquals([
            'X-Test-Header' => 'Test Value',
            'X-Second-Test' => 'Another Value',
        ], $webhook->getHeaders());
        $this->assertEquals(['Entry.auto_save'], $webhook->getTopics());

        $sys = $webhook->getSystemProperties();
        $this->assertEquals(new Link($this->readWriteSpaceId, 'Space'), $sys->getSpace());
        $this->assertEquals(new ApiDateTime('2017-06-13T08:30:13Z'), $sys->getCreatedAt());
        $this->assertEquals(new ApiDateTime('2017-06-13T08:30:51Z'), $sys->getUpdatedAt());
    }

    /**
     * @vcr e2e_webhook_get_collection.json
     */
    public function testGetWebhooks()
    {
        $client = $this->getReadWriteClient();
        $webhooks = $client->webhook->getAll();

        $this->assertInstanceOf(Webhook::class, $webhooks[0]);

        $query = (new Query())
            ->setLimit(1);
        $webhooks = $client->webhook->getAll($query);
        $this->assertInstanceOf(Webhook::class, $webhooks[0]);
        $this->assertCount(1, $webhooks);
    }

    /**
     * @return Webhook
     *
     * @vcr e2e_webhook_create_update.json
     */
    public function testCreateWebhook(): Webhook
    {
        $client = $this->getReadWriteClient();

        $webhook = (new Webhook('cf-webhook-X7v4Cy26RJ', 'https://www.example.com/cf-EtumCxGYNobexO7BCi6I6HSaxlpFf3d9YWNvRGb4'))
            ->addTopic('Entry.create')
            ->addHeader('X-Test-Header', 'Test Value')
            ->setHttpBasicUsername('cf-test-username')
            ->setHttpBasicPassword('cf-test-password')
        ;

        $startingWebhook = clone $webhook;

        $client->webhook->create($webhook);
        $this->assertNotNull($webhook->getId());

        $this->assertEquals($startingWebhook->getName(), $webhook->getName());
        $this->assertEquals($startingWebhook->getUrl(), $webhook->getUrl());
        $this->assertEquals($startingWebhook->getHttpBasicUsername(), $webhook->getHttpBasicUsername());
        $this->assertEquals(null, $webhook->getHttpBasicPassword());
        $this->assertEquals($startingWebhook->getHeaders(), $webhook->getHeaders());
        $this->assertEquals($startingWebhook->getTopics(), $webhook->getTopics());

        $webhook->setUrl('https://www.example.com/cf-xrLThVn5uBzHqB6tIbpV4aycgyisr5UAEQSafzkG');
        $startingWebhook = clone $webhook;

        $webhook->update();
        $this->assertEquals(1, $webhook->getSystemProperties()->getVersion());
        $this->assertEquals($startingWebhook->getName(), $webhook->getName());
        $this->assertEquals($startingWebhook->getUrl(), $webhook->getUrl());
        $this->assertEquals($startingWebhook->getHttpBasicUsername(), $webhook->getHttpBasicUsername());
        $this->assertEquals(null, $webhook->getHttpBasicPassword());
        $this->assertEquals($startingWebhook->getHeaders(), $webhook->getHeaders());
        $this->assertEquals($startingWebhook->getTopics(), $webhook->getTopics());

        return $webhook;
    }

    /**
     * @param Webhook $webook
     *
     * @return Webhook
     *
     * @depends testCreateWebhook
     * @vcr e2e_webhook_events_fired_and_logged.json
     */
    public function testWebhookEventsFiredAndLogged(Webhook $webhook): Webhook
    {
        $client = $this->getReadWriteClient();

        $entry1 = (new Entry('person'))
            ->setField('name', 'en-US', 'Burt Macklin')
            ->setField('jobTitle', 'en-US', 'FBI')
        ;
        $client->entry->create($entry1);
        $entry1->delete();

        $entry2 = (new Entry('person'))
            ->setField('name', 'en-US', 'Dwight Schrute')
            ->setField('jobTitle', 'en-US', 'Assistant Regional Manager')
        ;
        $client->entry->create($entry2);
        $entry2->delete();

        $webhookId = $webhook->getId();

        $health = $client->webhookHealth->get($webhookId);
        $this->assertEquals(new Link($health->getId(), 'Webhook'), $health->asLink());
        $this->assertEquals(2, $health->getTotal());
        $this->assertEquals(0, $health->getHealthy());
        $this->assertEquals($webhookId, $health->getId());

        $webhookCalls = $client->webhookCall->getAll($webhookId);

        $this->assertInstanceOf(WebhookCall::class, $webhookCalls[0]);
        $this->assertEquals(new Link($webhookCalls[0]->getId(), 'WebhookCallOverview'), $webhookCalls[0]->asLink());
        $this->assertEquals('create', $webhookCalls[0]->getEventType());
        $this->assertEquals('https://www.example.com/cf-xrLThVn5uBzHqB6tIbpV4aycgyisr5UAEQSafzkG', $webhookCalls[0]->getUrl());

        $query = (new Query())
            ->setLimit(1);
        $webhookCalls = $client->webhookCall->getAll($webhookId, $query);
        $this->assertInstanceOf(WebhookCall::class, $webhookCalls[0]);
        $this->assertEquals(404, $webhookCalls[0]->getStatusCode());
        $this->assertEquals('ClientError', $webhookCalls[0]->getError());
        // This is actually guaranteed thanks to type safety,
        // but it's the only meaningful test we can have
        $this->assertInstanceOf(ApiDateTime::class, $webhookCalls[0]->getRequestAt());
        $this->assertInstanceOf(ApiDateTime::class, $webhookCalls[0]->getResponseAt());
        $this->assertCount(1, $webhookCalls);

        $webhookCallId = $webhookCalls[0]->getId();
        $this->assertNotNull($webhookCallId);

        $webhookCall = $client->webhookCall->get($webhookId, $webhookCallId);
        $this->assertEquals(new Link($webhookCall->getId(), 'WebhookCallDetails'), $webhookCall->asLink());
        $requestPayload = json_decode((string) $webhookCall->getRequest()->getBody(), true);
        $this->assertEquals('Dwight Schrute', $requestPayload['fields']['name']['en-US']);
        $this->assertEquals('ContentManagement.Entry.create', $webhookCall->getRequest()->getHeaders()['X-Contentful-Topic'][0]);
        $this->assertEquals('ClientError', $webhookCall->getError());
        $this->assertEquals('create', $webhookCall->getEventType());
        $this->assertEquals(404, $webhookCall->getStatusCode());
        $this->assertEquals('https://www.example.com/cf-xrLThVn5uBzHqB6tIbpV4aycgyisr5UAEQSafzkG', $webhookCall->getUrl());
        $this->assertEquals(404, $webhookCall->getResponse()->getStatusCode());
        $this->assertEquals($webhookCallId, $webhookCall->getId());
        // This is actually guaranteed thanks to type safety,
        // but it's the only meaningful test we can have
        $this->assertInstanceOf(ApiDateTime::class, $webhookCall->getRequestAt());
        $this->assertInstanceOf(ApiDateTime::class, $webhookCall->getResponseAt());

        return $webhook;
    }

    /**
     * @param Webhook $webook
     *
     * @depends testWebhookEventsFiredAndLogged
     * @vcr e2e_webhook_delete.json
     */
    public function testDeleteWebhook(Webhook $webhook)
    {
        $webhook->delete();

        $this->markTestAsPassed();
    }
}
