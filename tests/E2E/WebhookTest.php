<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Core\Api\DateTimeImmutable;
use Contentful\Management\Query;
use Contentful\Management\Resource\Asset;
use Contentful\Management\Resource\Webhook;
use Contentful\Management\Resource\WebhookCall;
use Contentful\Tests\Management\BaseTestCase;

use function GuzzleHttp\json_decode as guzzle_json_decode;

class WebhookTest extends BaseTestCase
{
    /**
     * @vcr e2e_webhook_get_one.json
     */
    public function testGetOne()
    {
        $proxy = $this->getReadOnlySpaceProxy();

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
        $this->assertLink($this->readOnlySpaceId, 'Space', $sys->getSpace());
        $this->assertSame('2017-06-13T08:30:13Z', (string) $sys->getCreatedAt());
        $this->assertSame('2017-06-13T08:30:51Z', (string) $sys->getUpdatedAt());
    }

    /**
     * @vcr e2e_webhook_get_one_from_space.json
     */
    public function testGetOneFromSpace()
    {
        $space = $this->getReadOnlySpaceProxy()
            ->toResource()
        ;

        $webhook = $space->getWebhook('3tilCowN1lI1rDCe9vhK0C');

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
        $this->assertLink($this->readOnlySpaceId, 'Space', $sys->getSpace());
        $this->assertSame('2017-06-13T08:30:13Z', (string) $sys->getCreatedAt());
        $this->assertSame('2017-06-13T08:30:51Z', (string) $sys->getUpdatedAt());
    }

    /**
     * @vcr e2e_webhook_get_collection.json
     */
    public function testGetCollection()
    {
        $proxy = $this->getReadOnlySpaceProxy();
        $webhooks = $proxy->getWebhooks();

        $this->assertInstanceOf(Webhook::class, $webhooks[0]);

        $query = (new Query())
            ->setLimit(1)
        ;
        $webhooks = $proxy->getWebhooks($query);
        $this->assertInstanceOf(Webhook::class, $webhooks[0]);
        $this->assertCount(1, $webhooks);
    }

    /**
     * @vcr e2e_webhook_create_with_filters_and_transformations.json
     */
    public function testCreateWithFiltersAndTransformations()
    {
        $proxy = $this->getReadWriteSpaceProxy();

        $webhook = (new Webhook('cf-webhook-deleteme-filter-transform', 'https://www.example.com/cf-44mPCxGYNobexO7BCe4k6HSaxlpFf3d9YWNvRGb4'))
            ->addTopic('Entry.create')
            ->setTransformation(['method' => 'GET'])
            ->setFilters([
                new Webhook\NotFilter(
                    new Webhook\EqualityFilter('sys.environment.sys.id', 'master')
                ),
                new Webhook\InclusionFilter('sys.id', ['main_nav', 'footer_nav']),
                new Webhook\RegexpFilter('sys.contentType.sys.id', '^nav-.+$'),
            ])
        ;

        $proxy->create($webhook);

        $this->assertSame(['method' => 'GET'], $webhook->getTransformation());

        $filters = $webhook->getFilters();
        $this->assertContainsOnlyInstancesOf(Webhook\FilterInterface::class, $filters);
        $this->assertCount(3, $filters);

        /** @var Webhook\NotFilter $filter */
        $filter = $filters[0];
        $this->assertInstanceOf(Webhook\NotFilter::class, $filter);

        /** @var Webhook\EqualityFilter $child */
        $child = $filter->getChild();
        $this->assertInstanceOf(Webhook\EqualityFilter::class, $child);
        $this->assertSame('sys.environment.sys.id', $child->getDoc());
        $this->assertSame('master', $child->getValue());

        /** @var Webhook\InclusionFilter $filter */
        $filter = $filters[1];
        $this->assertInstanceOf(Webhook\InclusionFilter::class, $filter);
        $this->assertSame('sys.id', $filter->getDoc());
        $this->assertSame(['main_nav', 'footer_nav'], $filter->getValues());

        /** @var Webhook\RegexpFilter $filter */
        $filter = $filters[2];
        $this->assertInstanceOf(Webhook\RegexpFilter::class, $filter);
        $this->assertSame('sys.contentType.sys.id', $filter->getDoc());
        $this->assertSame('^nav-.+$', $filter->getPattern());

        $webhook->delete();
    }

    /**
     * @vcr e2e_webhook_create_update.json
     */
    public function testCreateUpdate(): Webhook
    {
        $proxy = $this->getReadWriteSpaceProxy();

        $webhook = (new Webhook('cf-webhook-deleteme', 'https://www.example.com/cf-EtumCxGYNobexO7BCi6I6HSaxlpFf3d9YWNvRGb4'))
            ->addTopic('Asset.create')
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
     * @depends testCreateUpdate
     *
     * @vcr e2e_webhook_events_fired_and_logged.json
     */
    public function testEventsFiredAndLogged(Webhook $webhook): Webhook
    {
        $proxy = $this->getReadWriteSpaceProxy();
        $environmentProxy = $proxy->getEnvironmentProxy('master');

        $asset1 = (new Asset())
            ->setTitle('en-US', 'Burt Macklin')
            ->setDescription('en-US', 'FBI')
        ;
        $environmentProxy->create($asset1);
        $asset1->delete();

        $asset2 = (new Asset())
            ->setTitle('en-US', 'Dwight Schrute')
            ->setDescription('en-US', 'Assistant (to the) Regional Manager')
        ;
        $environmentProxy->create($asset2);
        $asset2->delete();

        $webhookId = $webhook->getId();

        $health = $webhook->getHealth();
        $this->assertSame([
            'space' => $this->readWriteSpaceId,
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
            ->setLimit(1)
        ;
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
            'space' => $this->readWriteSpaceId,
            'webhook' => $webhookId,
            'call' => $webhookCallId,
        ], $webhookCall->asUriParameters());
        $this->assertLink($webhookCall->getId(), 'WebhookCallDetails', $webhookCall->asLink());
        $requestPayload = guzzle_json_decode((string) $webhookCall->getRequest()->getBody(), true);
        $this->assertSame('Dwight Schrute', $requestPayload['fields']['title']['en-US']);
        $this->assertSame('ContentManagement.Asset.create', $webhookCall->getRequest()->getHeaders()['X-Contentful-Topic'][0]);
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
     * @depends testEventsFiredAndLogged
     *
     * @vcr e2e_webhook_get_calls_from_space_proxy.json
     */
    public function testGetCallsFromSpaceProxy(Webhook $webhook)
    {
        $proxy = $this->getReadWriteSpaceProxy();
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
     * @depends testGetCallsFromSpaceProxy
     *
     * @vcr e2e_webhook_delete.json
     */
    public function testDelete(Webhook $webhook)
    {
        $webhook->delete();

        $this->markTestAsPassed();
    }
}
