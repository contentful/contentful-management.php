<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Integration\Resource;

use Contentful\Management\Resource\WebhookCall;
use Contentful\Management\ResourceBuilder;
use Contentful\Tests\Management\BaseTestCase;

class WebhookCallTest extends BaseTestCase
{
    public function testInvalidCreation()
    {
        $this->expectException(\Error::class);
        new WebhookCall();
    }

    /**
     * This test is meaningless.
     * It's only here because private, empty constructors are not correctly picked by code coverage.
     */
    public function testConstructor()
    {
        $class = new \ReflectionClass(WebhookCall::class);
        $object = $class->newInstanceWithoutConstructor();
        $constructor = $class->getConstructor();
        $constructor->setAccessible(true);
        $constructor->invoke($object);

        $this->markTestAsPassed();
    }

    public function testJsonSerialize(): WebhookCall
    {
        $resource = (new ResourceBuilder())
            ->build($this->getParsedFixture('serialize.json'))
        ;

        $this->assertJsonFixtureEqualsJsonObject('Integration/Resource/WebhookCall/serialize.json', $resource);

        return $resource;
    }

    /**
     * @depends testJsonSerialize
     */
    public function testInvalidUpdate(WebhookCall $webhookCall)
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Trying to update resource object in mapper of type \"Contentful\Management\Mapper\WebhookCall\", but only creation from scratch is supported.");

        (new ResourceBuilder())
            ->build(['sys' => [
                'type' => 'WebhookCallDetails',
            ]], $webhookCall)
        ;
    }

    /**
     * @depends testJsonSerialize
     */
    public function testInvalidConversionToRequestBody(WebhookCall $webhookCall)
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Trying to convert object of class \"Contentful\Management\Resource\WebhookCall\" to a request body format, but operation is not supported on this class.");

        $webhookCall->asRequestBody();
    }
}
