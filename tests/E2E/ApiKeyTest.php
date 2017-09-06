<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\E2E\Management;

use Contentful\Link;
use Contentful\Management\Query;
use Contentful\Management\Resource\DeliveryApiKey;
use Contentful\Management\Resource\PreviewApiKey;
use Contentful\Tests\End2EndTestCase;

class ApiKeyTest extends End2EndTestCase
{
    /**
     * @vcr e2e_api_key_delivery_get_one_collection.json
     */
    public function testGetDeliveryApiKey()
    {
        $manager = $this->getReadWriteSpaceManager();

        $deliveryApiKey = $manager->getDeliveryApiKey('1MwuwHlM9TXf3RXcsvMrjM');
        $this->assertEquals('Example API Key', $deliveryApiKey->getName());
        $this->assertNull($deliveryApiKey->getDescription());
        $this->assertEquals('5d58929d31be0aa63b051e0ce42a7c04e0e783f881482803157647ffafe0f30f', $deliveryApiKey->getAccessToken());
        $this->assertInstanceOf(Link::class, $deliveryApiKey->getPreviewApiKey());
        $this->assertEquals('1Mx3FqXX5XCJDtNpVW4BZI', $deliveryApiKey->getPreviewApiKey()->getId());

        $deliveryApiKeys = $manager->getDeliveryApiKeys();

        $this->assertCount(2, $deliveryApiKeys);
        $deliveryApiKey = $deliveryApiKeys[0];
        $this->assertInstanceOf(DeliveryApiKey::class, $deliveryApiKey);
        $this->assertEquals('Example API Key', $deliveryApiKey->getName());
        $this->assertNull($deliveryApiKey->getDescription());
        // When working with the collection endpoint,
        // each key's preview api key is *not* part of the payload.
        // This means that DeliveryApiKey objects created using
        // SpaceManager::getDeliveryApiKeys() will always have $previewApiKey
        // set to null.
        $this->assertNull($deliveryApiKey->getPreviewApiKey());

        $query = (new Query())
            ->setLimit(1);
        $deliveryApiKeys = $manager->getDeliveryApiKeys($query);

        $this->assertCount(1, $deliveryApiKeys);
        $deliveryApiKey = $deliveryApiKeys[0];
        $this->assertInstanceOf(DeliveryApiKey::class, $deliveryApiKey);
        $this->assertEquals('Example API Key', $deliveryApiKey->getName());
        $this->assertNull($deliveryApiKey->getDescription());
        $this->assertNull($deliveryApiKey->getPreviewApiKey());
    }

    /**
     * @vcr e2e_api_key_preview_get_one_collection.json
     */
    public function testGetPreviewApiKey()
    {
        $manager = $this->getReadWriteSpaceManager();

        $previewApiKey = $manager->getPreviewApiKey('1Mx3FqXX5XCJDtNpVW4BZI');
        $this->assertEquals('Preview Key', $previewApiKey->getName());
        $this->assertNull($previewApiKey->getDescription());
        $this->assertEquals('ee8b264bf66ca66e0c005411cff6009456b256d0011f617bfbe128d0f0c99f9f', $previewApiKey->getAccessToken());

        $previewApiKeys = $manager->getPreviewApiKeys();

        $this->assertCount(2, $previewApiKeys);
        $previewApiKey = $previewApiKeys[0];
        $this->assertInstanceOf(PreviewApiKey::class, $previewApiKey);
        $this->assertEquals('Preview Key', $previewApiKey->getName());
        $this->assertNull($previewApiKey->getDescription());

        $query = (new Query())
            ->setLimit(1);
        $previewApiKeys = $manager->getPreviewApiKeys($query);

        $this->assertCount(1, $previewApiKeys);
        $previewApiKey = $previewApiKeys[0];
        $this->assertInstanceOf(PreviewApiKey::class, $previewApiKey);
        $this->assertEquals('Preview Key', $previewApiKey->getName());
        $this->assertNull($previewApiKey->getDescription());
    }

    /**
     * @vcr e2e_api_key_create_update_delete.json
     */
    public function testCreateUpdateDelete()
    {
        $manager = $this->getReadWriteSpaceManager();

        $deliveryApiKey = new DeliveryApiKey('iOS');
        $deliveryApiKey->setDescription('A custom description');

        $manager->create($deliveryApiKey);

        $this->assertEquals('iOS', $deliveryApiKey->getName());
        $this->assertEquals('A custom description', $deliveryApiKey->getDescription());
        $this->assertInstanceOf(Link::class, $deliveryApiKey->getPreviewApiKey());

        $deliveryApiKey->setName('Website');
        $manager->update($deliveryApiKey);

        $this->assertEquals('Website', $deliveryApiKey->getName());

        $manager->delete($deliveryApiKey);
    }
}
