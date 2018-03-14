<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Core\Api\DateTimeImmutable;
use Contentful\Management\Query;
use Contentful\Management\Resource\Entry;
use Contentful\Management\Resource\Webhook;
use Contentful\Management\Resource\WebhookCall;
use Contentful\Tests\Management\BaseTestCase;
use function GuzzleHttp\json_decode as guzzle_json_decode;

class WebhookTest extends BaseTestCase
{
    /**
     * @vcr e2e_webhook_get_one.json
     */
    public function testGetWebhook()
    {
        $proxy = $this->getDefaultSpaceProxy();

        $webhook = $proxy->getWebhook('3tilCowN1lI1rDCe9vhK0C');

        $this->assertLink('3tilCowN1lI1rDCe9vhK0C', 'WebhookDefinition', $webhook->asLink());
        $this->assertSame('Default Webhook', $webhook->getName());
        $this->assertSame('https://www.example.com/default-webhook', $webhook->getUrl());
        $this->assertSame('default_username', $webhook->getHttpBasicUsername());
        $this->assertSame([
            'X-Test-Header' => 'Test Value',
            'X-Second-Test' => 'Another Value',
        ], $webhook->getHeaders());
        $this->assertSame(['Entry.auto_save'], $webhook->getTopics());

        $sys = $webhook->getSystemProperties();
        $this->assertLink($this->defaultSpaceId, 'Space', $sys->getSpace());
        $this->assertSame('2017-06-13T08:30:13Z', (string) $sys->getCreatedAt());
        $this->assertSame('2017-06-13T08:30:51Z', (string) $sys->getUpdatedAt());
    }

    /**
     * @vcr e2e_webhook_get_collection.json
     */
    public function testGetWebhooks()
    {
        $proxy = $this->getDefaultSpaceProxy();
        $webhooks = $proxy->getWebhooks();

        $this->assertInstanceOf(Webhook::class, $webhooks[0]);

        $query = (new Query())
            ->setLimit(1);
        $webhooks = $proxy->getWebhooks($query);
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
        $proxy = $this->getDefaultSpaceProxy();

        $webhook = (new Webhook('cf-webhook-X7v4Cy26RJ', 'https://www.example.com/cf-EtumCxGYNobexO7BCi6I6HSaxlpFf3d9YWNvRGb4'))
            ->addTopic('Entry.create')
            ->addHeader('X-Test-Header', 'Test Value')
            ->setHttpBasicUsername('cf-test-username')
            ->setHttpBasicPassword('cf-test-password')
        ;

        $startingWebhook = clone $webhook;

        $proxy->create($webhook);
        $this->assertNotNull($webhook->getId());

        $this->assertSame($startingWebhook->getName(), $webhook->getName());
        $this->assertSame($startingWebhook->getUrl(), $webhook->getUrl());
        $this->assertSame($startingWebhook->getHttpBasicUsername(), $webhook->getHttpBasicUsername());
        $this->assertNull($webhook->getHttpBasicPassword());
        $this->assertSame($startingWebhook->getHeaders(), $webhook->getHeaders());
        $this->assertSame($startingWebhook->getTopics(), $webhook->getTopics());

        $webhook->setUrl('https://www.example.com/cf-xrLThVn5uBzHqB6tIbpV4aycgyisr5UAEQSafzkG');
        $startingWebhook = clone $webhook;

        $webhook->update();
        $this->assertSame(1, $webhook->getSystemProperties()->getVersion());
        $this->assertSame($startingWebhook->getName(), $webhook->getName());
        $this->assertSame($startingWebhook->getUrl(), $webhook->getUrl());
        $this->assertSame($startingWebhook->getHttpBasicUsername(), $webhook->getHttpBasicUsername());
        $this->assertNull($webhook->getHttpBasicPassword());
        $this->assertSame($startingWebhook->getHeaders(), $webhook->getHeaders());
        $this->assertSame($startingWebhook->getTopics(), $webhook->getTopics());

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
        $proxy = $this->getDefaultSpaceProxy();

        $entry1 = (new Entry('person'))
            ->setField('name', 'en-US', 'Burt Macklin')
            ->setField('jobTitle', 'en-US', 'FBI')
        ;
        $proxy->create($entry1);
        $entry1->delete();

        $entry2 = (new Entry('person'))
            ->setField('name', 'en-US', 'Dwight Schrute')
            ->setField('jobTitle', 'en-US', 'Assistant Regional Manager')
        ;
        $proxy->create($entry2);
        $entry2->delete();

        $webhookId = $webhook->getId();

        $health = $webhook->getHealth();
        $this->assertSame([
            'space' => $this->defaultSpaceId,
            'webhook' => $webhookId,
        ], $health->asUriParameters());
        $this->assertLink($health->getId(), 'Webhook', $health->asLink());
        $this->assertSame(2, $health->getTotal());
        $this->assertSame(0, $health->getHealthy());
        $this->assertSame($webhookId, $health->getId());

        $webhookCalls = $webhook->getCalls();

        $this->assertInstanceOf(WebhookCall::class, $webhookCalls[0]);
        $this->assertLink($webhookCalls[0]->getId(), 'WebhookCallOverview', $webhookCalls[0]->asLink());
        $this->assertSame('create', $webhookCalls[0]->getEventType());
        $this->assertSame('https://www.example.com/cf-xrLThVn5uBzHqB6tIbpV4aycgyisr5UAEQSafzkG', $webhookCalls[0]->getUrl());

        $query = (new Query())
            ->setLimit(1);
        $webhookCalls = $webhook->getCalls($query);
        $this->assertInstanceOf(WebhookCall::class, $webhookCalls[0]);
        $this->assertSame(404, $webhookCalls[0]->getStatusCode());
        $this->assertSame('ClientError', $webhookCalls[0]->getError());
        // This is actually guaranteed thanks to type safety,
        // but it's the only meaningful test we can have
        $this->assertInstanceOf(DateTimeImmutable::class, $webhookCalls[0]->getRequestAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $webhookCalls[0]->getResponseAt());
        $this->assertCount(1, $webhookCalls);

        $webhookCallId = $webhookCalls[0]->getId();
        $this->assertNotNull($webhookCallId);

        $webhookCall = $webhook->getCall($webhookCallId);
        $this->assertSame([
            'space' => $this->defaultSpaceId,
            'webhook' => $webhookId,
            'call' => $webhookCallId,
        ], $webhookCall->asUriParameters());
        $this->assertLink($webhookCall->getId(), 'WebhookCallDetails', $webhookCall->asLink());
        $requestPayload = guzzle_json_decode((string) $webhookCall->getRequest()->getBody(), true);
        $this->assertSame('Dwight Schrute', $requestPayload['fields']['name']['en-US']);
        $this->assertSame('ContentManagement.Entry.create', $webhookCall->getRequest()->getHeaders()['X-Contentful-Topic'][0]);
        $this->assertSame('ClientError', $webhookCall->getError());
        $this->assertSame('create', $webhookCall->getEventType());
        $this->assertSame(404, $webhookCall->getStatusCode());
        $this->assertSame('https://www.example.com/cf-xrLThVn5uBzHqB6tIbpV4aycgyisr5UAEQSafzkG', $webhookCall->getUrl());
        $this->assertSame(404, $webhookCall->getResponse()->getStatusCode());
        $this->assertSame($webhookCallId, $webhookCall->getId());
        // This is actually guaranteed thanks to type safety,
        // but it's the only meaningful test we can have
        $this->assertInstanceOf(DateTimeImmutable::class, $webhookCall->getRequestAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $webhookCall->getResponseAt());

        return $webhook;
    }

    /**
     * @param Webhook $webhook
     *
     * @depends testWebhookEventsFiredAndLogged
     * @vcr e2e_webhook_get_from_space_proxy.json
     */
    public function testGetCallsFromSpaceProxy(Webhook $webhook)
    {
        $proxy = $this->getDefaultSpaceProxy();
        $webhookId = $webhook->getId();

        $health = $proxy->getWebhookHealth($webhookId);
        $this->assertLink($health->getId(), 'Webhook', $health->asLink());
        $this->assertSame(2, $health->getTotal());
        $this->assertSame(0, $health->getHealthy());
        $this->assertSame($webhookId, $health->getId());

        $webhookCalls = $proxy->getWebhookCalls($webhookId);

        $callId = $webhookCalls[0]->getId();
        $this->assertNotNull($callId);

        $webhookCall = $proxy->getWebhookCall($webhookId, $callId);
        $this->assertLink($webhookCall->getId(), 'WebhookCallDetails', $webhookCall->asLink());

        return $webhook;
    }

    /**
     * @param Webhook $webook
     *
     * @depends testGetCallsFromSpaceProxy
     * @vcr e2e_webhook_delete.json
     */
    public function testDeleteWebhook(Webhook $webhook)
    {
        $webhook->delete();

        $this->markTestAsPassed();
    }
}
