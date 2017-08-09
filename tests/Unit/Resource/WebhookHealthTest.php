<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\Unit\Resource;

use Contentful\Management\Resource\WebhookHealth;
use Contentful\Management\ResourceBuilder;
use PHPUnit\Framework\TestCase;

class WebhookHealthTest extends TestCase
{
    public function testJsonSerialize()
    {
        $webhookHealth = new WebhookHealth();
        $json = '{"sys":{"type":"Webhook"},"calls":{"total":0,"healthy":0}}';
        $this->assertJsonStringEqualsJsonString($json, json_encode($webhookHealth));

        $json = '{"sys":{"type":"Webhook"},"calls":{"total":233,"healthy":102}}';

        $webhookHealth = (new ResourceBuilder())
            ->buildObjectsFromRawData(['sys' => ['type' => 'Webhook'], 'calls' => ['total' => 233, 'healthy' => 102]]);

        $this->assertJsonStringEqualsJsonString($json, json_encode($webhookHealth));
    }
}
