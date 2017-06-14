<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\E2E;

use Contentful\Link;
use Contentful\Management\Entry;
use Contentful\Management\Query;
use Contentful\Management\Webhook;
use Contentful\Management\WebhookCall;
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
     * @vcr e2e_webhook_create_update.json
     */
    public function testCreateWebhook()
    {
        $manager = $this->getReadWriteSpaceManager();

        $webhook = (new Webhook('cf-webhook-X7v4Cy26RJ', 'https://www.example.com/cf-EtumCxGYNobexO7BCi6I6HSaxlpFf3d9YWNvRGb4'))
            ->addTopic('Entry.create')
        ;

        $manager->create($webhook);
        $this->assertNotNull($webhook->getSystemProperties()->getId());

        $webhook->setUrl('https://www.example.com/cf-xrLThVn5uBzHqB6tIbpV4aycgyisr5UAEQSafzkG');
        $manager->update($webhook);
        $this->assertEquals(1, $webhook->getSystemProperties()->getVersion());

        return $webhook;
    }

    /**
     * @depends testCreateWebhook
     * @vcr e2e_webhook_delete.json
     */
    public function testDeleteWebhook(Webhook $webhook)
    {
        $manager = $this->getReadWriteSpaceManager();

        $manager->delete($webhook);
    }
}
