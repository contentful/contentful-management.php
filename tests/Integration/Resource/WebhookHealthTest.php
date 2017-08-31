<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Integration\Management\Resource;

use Contentful\Management\Resource\WebhookHealth;
use Contentful\Management\ResourceBuilder;
use PHPUnit\Framework\TestCase;

class WebhookHealthTest extends TestCase
{
    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Class "Contentful\Management\Resource\WebhookHealth" can only be instantiated as a result of an API call, manual creation is not allowed.
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
     * @expectedExceptionMessage Trying to update resource object in mapper of type "Contentful\Management\Mapper\WebhookHealth", but only creation from scratch is supported.
     */
    public function testInvalidUpdate(WebhookHealth $webhookHealth)
    {
        (new ResourceBuilder())
            ->build(['sys' => [
                'type' => 'Webhook',
            ]], $webhookHealth);
    }
}
