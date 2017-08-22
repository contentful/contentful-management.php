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
use Contentful\Management\Resource\WebhookHealth;
use PHPUnit\Framework\TestCase;

class WebhookHealthTest extends TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testInvalidCreation()
    {
        new WebhookHealth();
    }

    /**
     * @return WebhookHealth
     */
    public function testJsonSerialize(): WebhookHealth
    {
        $webhookHealth = (new ResourceBuilder())->build([
            'sys' => [
                'type' => 'Webhook',
            ],
            'calls' => [
                'total' => 233,
                'healthy' => 102,
                ],
            ]
        );

        $json = '{"sys":{"type":"Webhook"},"calls":{"total":233,"healthy":102}}';

        $this->assertJsonStringEqualsJsonString($json, json_encode($webhookHealth));

        return $webhookHealth;
    }

    /**
     * @param WebhookHealth $webhookHealth
     *
     * @depends testJsonSerialize
     * @expectedException \LogicException
     */
    public function testInvalidUpdate(WebhookHealth $webhookHealth)
    {
        (new ResourceBuilder())
            ->build(['sys' => [
                'type' => 'Webhook',
            ]], $webhookHealth);
    }
}
