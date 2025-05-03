<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Core\Api\Link;
use Contentful\Management\Query;
use Contentful\Management\Resource\DeliveryApiKey;
use Contentful\Management\Resource\Environment;
use Contentful\Management\Resource\PreviewApiKey;
use Contentful\Tests\Management\BaseTestCase;

class ApiKeyTest extends BaseTestCase
{
    /**
     * @vcr e2e_api_key_delivery_get_one_collection.json
     */
    public function testDeliveryGetOneCollection()
    {
        $proxy = $this->getReadOnlySpaceProxy();

        $deliveryApiKey = $proxy->getDeliveryApiKey('1MwuwHlM9TXf3RXcsvMrjM');
        $this->assertSame('Example API Key', $deliveryApiKey->getName());
        $this->assertNull($deliveryApiKey->getDescription());
        $this->assertSame('5d58929d31be0aa63b051e0ce42a7c04e0e783f881482803157647ffafe0f30f', $deliveryApiKey->getAccessToken());
        $this->assertInstanceOf(Link::class, $deliveryApiKey->getPreviewApiKey());
        $this->assertSame('1Mx3FqXX5XCJDtNpVW4BZI', $deliveryApiKey->getPreviewApiKey()->getId());

        $deliveryApiKeys = $proxy->getDeliveryApiKeys();

        $this->assertCount(2, $deliveryApiKeys);
        $deliveryApiKey = $deliveryApiKeys[0];
        $this->assertInstanceOf(DeliveryApiKey::class, $deliveryApiKey);
        $this->assertSame('Example API Key', $deliveryApiKey->getName());
        $this->assertNull($deliveryApiKey->getDescription());
        $this->assertInstanceOf(Link::class, $deliveryApiKey->getPreviewApiKey());

        $query = (new Query())
            ->setLimit(1)
        ;
        $deliveryApiKeys = $proxy->getDeliveryApiKeys($query);

        $this->assertCount(1, $deliveryApiKeys);
        $deliveryApiKey = $deliveryApiKeys[0];
        $this->assertInstanceOf(DeliveryApiKey::class, $deliveryApiKey);
        $this->assertSame('Example API Key', $deliveryApiKey->getName());
        $this->assertNull($deliveryApiKey->getDescription());
        $this->assertInstanceOf(Link::class, $deliveryApiKey->getPreviewApiKey());

        $this->assertSame([
            'space' => $this->readOnlySpaceId,
            'deliveryApiKey' => '1MwuwHlM9TXf3RXcsvMrjM',
        ], $deliveryApiKey->asUriParameters());
    }

    /**
     * @vcr e2e_api_key_preview_get_one_collection.json
     */
    public function testPreviewGetOneCollection()
    {
        $proxy = $this->getReadOnlySpaceProxy();

        $previewApiKey = $proxy->getPreviewApiKey('1Mx3FqXX5XCJDtNpVW4BZI');
        $this->assertSame('Preview Key', $previewApiKey->getName());
        $this->assertNull($previewApiKey->getDescription());
        $this->assertSame('ee8b264bf66ca66e0c005411cff6009456b256d0011f617bfbe128d0f0c99f9f', $previewApiKey->getAccessToken());

        $previewApiKeys = $proxy->getPreviewApiKeys();

        $this->assertCount(2, $previewApiKeys);
        $previewApiKey = $previewApiKeys[0];
        $this->assertInstanceOf(PreviewApiKey::class, $previewApiKey);
        $this->assertSame('Preview Key', $previewApiKey->getName());
        $this->assertNull($previewApiKey->getDescription());

        $query = (new Query())
            ->setLimit(1)
        ;
        $previewApiKeys = $proxy->getPreviewApiKeys($query);

        $this->assertCount(1, $previewApiKeys);
        $previewApiKey = $previewApiKeys[0];
        $this->assertInstanceOf(PreviewApiKey::class, $previewApiKey);
        $this->assertSame('Preview Key', $previewApiKey->getName());
        $this->assertNull($previewApiKey->getDescription());

        $this->assertSame([
            'space' => $this->readOnlySpaceId,
            'previewApiKey' => '1Mx3FqXX5XCJDtNpVW4BZI',
        ], $previewApiKey->asUriParameters());
    }

    /**
     * @vcr e2e_api_key_create_update_delete.json
     */
    public function testCreateUpdateDelete()
    {
        $proxy = $this->getReadWriteSpaceProxy();

        $deliveryApiKey = new DeliveryApiKey('iOS');
        $deliveryApiKey->setDescription('A custom description');

        $proxy->create($deliveryApiKey);

        $this->assertSame('iOS', $deliveryApiKey->getName());
        $this->assertSame('A custom description', $deliveryApiKey->getDescription());
        $this->assertInstanceOf(Link::class, $deliveryApiKey->getPreviewApiKey());

        $deliveryApiKey->setName('Website');
        $deliveryApiKey->update();

        $this->assertSame('Website', $deliveryApiKey->getName());

        $deliveryApiKey->delete();
    }

    /**
     * @vcr e2e_api_key_with_environments.json
     */
    public function testWithEnvironments()
    {
        $proxy = $this->getReadWriteSpaceProxy();

        // We start by making sure that there's only one environment available (master).
        $this->assertCount(1, $proxy->getEnvironments());

        $deliveryApiKey = new DeliveryApiKey('[TEMP] Key before environments');
        $proxy->create($deliveryApiKey);

        $this->assertNotNull($deliveryApiKey->getSystemProperties()->getId());

        // The API does not provide the environments property when there's only 1 environment in use.
        // The SDK normalizes this behavior and always returns the property.
        $this->assertCount(1, $deliveryApiKey->getEnvironments());
        $this->assertLink('master', 'Environment', $deliveryApiKey->getEnvironments()[0]);

        /** @var PreviewApiKey $previewApiKey */
        $previewApiKey = $proxy->resolveLink($deliveryApiKey->getPreviewApiKey());
        $this->assertCount(1, $previewApiKey->getEnvironments());
        $this->assertLink('master', 'Environment', $previewApiKey->getEnvironments()[0]);

        $deliveryApiKey->delete();

        // Now we create a throwaway environment to check that the API correctly creates the key.
        $environment = new Environment('[TEMP] Throwaway environment');

        $proxy->create($environment, 'tempEnvApiKeysTest');

        $environmentId = $environment->getId();
        $this->assertNotNull($environmentId);

        // Polls the API until the environment is ready.
        // Limit is used because repeated requests will be recorded
        // and the same response will be returned
        $limit = 5;
        while (true) {
            $query = (new Query())
                ->setLimit($limit)
            ;

            foreach ($proxy->getEnvironments($query) as $resultEnvironment) {
                if ($environmentId === $resultEnvironment->getId()) {
                    $environment = $resultEnvironment;
                    break;
                }
            }

            if ('ready' === $environment->getSystemProperties()->getStatus()->getId()) {
                break;
            }

            // This is arbitrary
            if ($limit > 50) {
                throw new \RuntimeException('Repeated requests are not yielding a ready environment, something is wrong.');
            }
            ++$limit;
            \usleep(500000);
        }

        // If now environments property is set, the key will only be configured for master.
        $deliveryApiKey = new DeliveryApiKey('[TEMP] Key after environments (1/2)');
        $proxy->create($deliveryApiKey);

        $this->assertNotNull($deliveryApiKey->getSystemProperties()->getId());

        $this->assertCount(1, $deliveryApiKey->getEnvironments());
        $this->assertLink('master', 'Environment', $deliveryApiKey->getEnvironments()[0]);

        /** @var PreviewApiKey $previewApiKey */
        $previewApiKey = $proxy->resolveLink($deliveryApiKey->getPreviewApiKey());
        $this->assertCount(1, $previewApiKey->getEnvironments());
        $this->assertLink('master', 'Environment', $previewApiKey->getEnvironments()[0]);

        // We add an environment and check that the key is handled correctly.
        $deliveryApiKey->addEnvironment(new Link('tempEnvApiKeysTest', 'Environment'));
        $deliveryApiKey->update();

        $this->assertCount(2, $deliveryApiKey->getEnvironments());
        $this->assertLink('master', 'Environment', $deliveryApiKey->getEnvironments()[0]);
        $this->assertLink($environmentId, 'Environment', $deliveryApiKey->getEnvironments()[1]);

        $deliveryApiKey->delete();

        // Let's try from scratch, defining immediately a key with 2 environments available.
        $deliveryApiKey = new DeliveryApiKey('[TEMP] Key after environments (2/2)');
        $deliveryApiKey->setEnvironments([
            new Link('master', 'Environment'),
            new Link('tempEnvApiKeysTest', 'Environment'),
        ]);
        $proxy->create($deliveryApiKey);

        $this->assertNotNull($deliveryApiKey->getSystemProperties()->getId());

        $this->assertCount(2, $deliveryApiKey->getEnvironments());
        $this->assertLink('master', 'Environment', $deliveryApiKey->getEnvironments()[0]);
        $this->assertLink($environmentId, 'Environment', $deliveryApiKey->getEnvironments()[1]);

        /** @var PreviewApiKey $previewApiKey */
        $previewApiKey = $proxy->resolveLink($deliveryApiKey->getPreviewApiKey());
        $this->assertCount(2, $previewApiKey->getEnvironments());
        $this->assertLink('master', 'Environment', $previewApiKey->getEnvironments()[0]);
        $this->assertLink($environmentId, 'Environment', $previewApiKey->getEnvironments()[1]);

        // Cleanup
        $deliveryApiKey->delete();
        $environment->delete();
    }
}
