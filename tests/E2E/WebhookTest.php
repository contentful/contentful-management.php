<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\E2E;

use Contentful\Link;
use Contentful\Management\Query;
use Contentful\Management\Resource\Entry;
use Contentful\Management\Resource\Webhook;
use Contentful\Management\Resource\WebhookCall;
use Contentful\Tests\End2EndTestCase;

class WebhookTest extends End2EndTestCase
{
    /**
     * @vcr e2e_webhook_get.json
     */
    public function testGetWebhook()
    {
        $manager = $this->getReadWriteSpaceManager();

        $webhook = $manager->getWebhook('3tilCowN1lI1rDCe9vhK0C');

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
        $this->assertEquals(new \DateTimeImmutable('2017-06-13T08:30:13Z'), $sys->getCreatedAt());
        $this->assertEquals(new \DateTimeImmutable('2017-06-13T08:30:51Z'), $sys->getUpdatedAt());
    }

    /**
     * @vcr e2e_webhook_get_collection.json
     */
    public function testGetWebhooks()
    {
        $manager = $this->getReadWriteSpaceManager();
        $webhooks = $manager->getWebhooks();

        $this->assertInstanceOf(Webhook::class, $webhooks[0]);

        $query = (new Query())
            ->setLimit(1);
        $webhooks = $manager->getWebhooks($query);
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
        $manager = $this->getReadWriteSpaceManager();

        $webhook = (new Webhook('cf-webhook-X7v4Cy26RJ', 'https://www.example.com/cf-EtumCxGYNobexO7BCi6I6HSaxlpFf3d9YWNvRGb4'))
            ->addTopic('Entry.create')
            ->addHeader('X-Test-Header', 'Test Value')
            ->setHttpBasicUsername('cf-test-username')
            ->setHttpBasicPassword('cf-test-password')
        ;

        $startingWebhook = clone $webhook;

        $manager->create($webhook);
        $this->assertNotNull($webhook->getSystemProperties()->getId());

        $this->assertEquals($startingWebhook->getName(), $webhook->getName());
        $this->assertEquals($startingWebhook->getUrl(), $webhook->getUrl());
        $this->assertEquals($startingWebhook->getHttpBasicUsername(), $webhook->getHttpBasicUsername());
        $this->assertEquals(null, $webhook->getHttpBasicPassword());
        $this->assertEquals($startingWebhook->getHeaders(), $webhook->getHeaders());
        $this->assertEquals($startingWebhook->getTopics(), $webhook->getTopics());

        $webhook->setUrl('https://www.example.com/cf-xrLThVn5uBzHqB6tIbpV4aycgyisr5UAEQSafzkG');
        $startingWebhook = clone $webhook;

        $manager->update($webhook);
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
        $manager = $this->getReadWriteSpaceManager();

        $entry1 = (new Entry('person'))
            ->setField('name', 'en-US', 'Burt Macklin')
            ->setField('jobTitle', 'en-US', 'FBI')
        ;
        $manager->create($entry1);
        $manager->delete($entry1);

        $entry2 = (new Entry('person'))
            ->setField('name', 'en-US', 'Dwight Schrute')
            ->setField('jobTitle', 'en-US', 'Assistant Regional Manager')
        ;
        $manager->create($entry2);
        $manager->delete($entry2);

        $webhookId = $webhook->getSystemProperties()->getId();

        $health = $manager->getWebhookHealth($webhookId);
        $this->assertEquals(new Link($health->getSystemProperties()->getId(), 'Webhook'), $health->asLink());
        $this->assertEquals(2, $health->getTotal());
        $this->assertEquals(0, $health->getHealthy());
        $this->assertEquals($webhookId, $health->getSystemProperties()->getId());

        $webhookCalls = $manager->getWebhookCalls($webhookId);

        $this->assertInstanceOf(WebhookCall::class, $webhookCalls[0]);
        $this->assertEquals(new Link($webhookCalls[0]->getSystemProperties()->getId(), 'WebhookCallOverview'), $webhookCalls[0]->asLink());
        $this->assertEquals('create', $webhookCalls[0]->getEventType());
        $this->assertEquals('https://www.example.com/cf-xrLThVn5uBzHqB6tIbpV4aycgyisr5UAEQSafzkG', $webhookCalls[0]->getUrl());

        $query = (new Query())
            ->setLimit(1);
        $webhookCalls = $manager->getWebhookCalls($webhookId, $query);
        $this->assertInstanceOf(WebhookCall::class, $webhookCalls[0]);
        $this->assertEquals(404, $webhookCalls[0]->getStatusCode());
        $this->assertEquals('ClientError', $webhookCalls[0]->getError());
        // This is actually guaranteed thanks to type safety,
        // but it's the only meaningful test we can have
        $this->assertInstanceOf(\DateTimeImmutable::class, $webhookCalls[0]->getRequestAt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $webhookCalls[0]->getResponseAt());
        $this->assertCount(1, $webhookCalls);

        $webhookCallId = $webhookCalls[0]->getSystemProperties()->getId();
        $this->assertNotNull($webhookCallId);

        $webhookCallDetails = $manager->getWebhookCallDetails($webhookId, $webhookCallId);
        $this->assertEquals(new Link($webhookCallDetails->getSystemProperties()->getId(), 'WebhookCallDetails'), $webhookCallDetails->asLink());
        $requestPayload = json_decode((string) $webhookCallDetails->getRequest()->getBody(), true);
        $this->assertEquals('Dwight Schrute', $requestPayload['fields']['name']['en-US']);
        $this->assertEquals('ContentManagement.Entry.create', $webhookCallDetails->getRequest()->getHeaders()['X-Contentful-Topic'][0]);
        $this->assertEquals('ClientError', $webhookCallDetails->getError());
        $this->assertEquals('create', $webhookCallDetails->getEventType());
        $this->assertEquals(404, $webhookCallDetails->getStatusCode());
        $this->assertEquals('https://www.example.com/cf-xrLThVn5uBzHqB6tIbpV4aycgyisr5UAEQSafzkG', $webhookCallDetails->getUrl());
        $this->assertEquals(404, $webhookCallDetails->getResponse()->getStatusCode());
        $this->assertEquals($webhookCallId, $webhookCallDetails->getSystemProperties()->getId());
        // This is actually guaranteed thanks to type safety,
        // but it's the only meaningful test we can have
        $this->assertInstanceOf(\DateTimeImmutable::class, $webhookCallDetails->getRequestAt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $webhookCallDetails->getResponseAt());

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
        $manager = $this->getReadWriteSpaceManager();

        $manager->delete($webhook);

        $this->markTestAsPassed();
    }
}
