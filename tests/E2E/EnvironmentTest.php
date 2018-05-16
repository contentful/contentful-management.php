<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Core\Exception\NotFoundException;
use Contentful\Core\Resource\ResourceArray;
use Contentful\Management\Query;
use Contentful\Management\Resource\Environment;
use Contentful\Tests\Management\BaseTestCase;

class EnvironmentTest extends BaseTestCase
{
    /**
     * @vcr e2e_environment_get_one.json
     */
    public function testGetOne()
    {
        $proxy = $this->getDefaultSpaceProxy();

        $environment = $proxy->getEnvironment('master');

        $this->assertInstanceOf(Environment::class, $environment);
        $this->assertLink('master', 'Environment', $environment->asLink());
        $sys = $environment->getSystemProperties();
        $this->assertSame('master', $sys->getId());
        $this->assertSame('Environment', $sys->getType());
        $this->assertSame('2017-12-07T11:07:09Z', (string) $sys->getCreatedAt());
        $this->assertSame('2018-03-19T09:59:34Z', (string) $sys->getUpdatedAt());
        $this->assertSame(3, $sys->getVersion());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getCreatedBy());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getUpdatedBy());
        $this->assertLink($this->defaultSpaceId, 'Space', $sys->getSpace());
        $this->assertSame('master', $environment->getName());
    }

    /**
     * @vcr e2e_environment_get_one_from_environment_proxy.json
     */
    public function testGetOneFromEnvironmentProxy()
    {
        $proxy = $this->getDefaultEnvironmentProxy();

        $environment = $proxy->toResource();

        $this->assertInstanceOf(Environment::class, $environment);
        $this->assertLink('master', 'Environment', $environment->asLink());
        $sys = $environment->getSystemProperties();
        $this->assertSame('master', $sys->getId());
        $this->assertSame('Environment', $sys->getType());
        $this->assertSame('2017-12-07T11:07:09Z', (string) $sys->getCreatedAt());
        $this->assertSame('2018-03-19T09:59:34Z', (string) $sys->getUpdatedAt());
        $this->assertSame(3, $sys->getVersion());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getCreatedBy());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getUpdatedBy());
        $this->assertLink($this->defaultSpaceId, 'Space', $sys->getSpace());
        $this->assertSame('master', $environment->getName());
    }

    /**
     * @vcr e2e_environment_get_collection.json
     */
    public function testGetCollection()
    {
        $proxy = $this->getDefaultSpaceProxy();

        $environments = $proxy->getEnvironments();

        $this->assertInstanceOf(ResourceArray::class, $environments);
        $this->assertInstanceOf(Environment::class, $environments[0]);

        $query = (new Query())
            ->setLimit(1);
        $environments = $proxy->getEnvironments($query);
        $this->assertInstanceOf(Environment::class, $environments[0]);
        $this->assertCount(1, $environments);
    }

    /**
     * @vcr e2e_environment_create_update_delete.json
     */
    public function testCreateUpdateDelete()
    {
        $proxy = $this->getDefaultSpaceProxy();

        $environment = new Environment('QA Environment');

        $proxy->create($environment);

        $environmentId = $environment->getId();
        $this->assertNotNull($environmentId);
        $this->assertSame('QA Environment', $environment->getName());
        $this->assertSame(1, $environment->getSystemProperties()->getVersion());
        $this->assertLink('queued', 'Status', $environment->getSystemProperties()->getStatus());

        // Polls the API until the environment is ready.
        // Limit is used because repeated requests will be recorded
        // and the same response will be returned
        $limit = 5;
        do {
            $query = (new Query())
                ->setLimit($limit);

            foreach ($proxy->getEnvironments($query) as $resultEnvironment) {
                if ($environmentId === $resultEnvironment->getId()) {
                    $environment = $resultEnvironment;
                    break;
                }
            }

            // This is arbitrary
            if ($limit > 50) {
                throw new \RuntimeException(
                    'Repeated requests are not yielding a ready environment, something is wrong.'
                );
            }
            ++$limit;
        } while ('ready' !== $environment->getSystemProperties()->getStatus()->getId());

        $environment->setName('CI Environment');

        $environment->update();
        $this->assertSame($environmentId, $environment->getId());
        $this->assertSame('CI Environment', $environment->getName());

        $environment->delete();

        try {
            $proxy->getEnvironment($environmentId);

            $this->fail('Trying to get a non-existing environment did not throw an exception');
        } catch (\Exception $exception) {
            $this->assertInstanceOf(NotFoundException::class, $exception);
            $this->assertSame('The resource could not be found.', $exception->getMessage());
        }
    }
}
