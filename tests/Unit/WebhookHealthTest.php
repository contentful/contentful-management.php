<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\Unit;

use Contentful\Management\WebhookHealth;
use Contentful\Management\ResourceBuilder;

class WebhookHealthTest extends \PHPUnit_Framework_TestCase
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
