<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\Unit\Resource;

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

    public function testJsonSerialize()
    {
        $webhookHealth = (new ResourceBuilder())->buildObjectsFromRawData([
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
    }
}
