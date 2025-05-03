<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management;

use Contentful\Management\Client;
use Contentful\Management\Proxy\EnvironmentProxy;
use Contentful\Management\Proxy\SpaceProxy;
use Contentful\Tests\TestCase;

use function GuzzleHttp\json_encode as guzzle_json_encode;

class BaseTestCase extends TestCase
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $readOnlySpaceId;

    /**
     * @var string
     */
    protected $readWriteSpaceId;

    /**
     * @var string
     */
    protected $codeGeneratorSpaceId;

    /**
     * @var string
     */
    protected $organizationId;

    protected function setUp(): void
    {
        $this->token = \getenv('CONTENTFUL_PHP_MANAGEMENT_TEST_TOKEN');

        $this->readOnlySpaceId = \getenv('CONTENTFUL_PHP_MANAGEMENT_SPACE_ID_READ_ONLY');
        $this->readWriteSpaceId = \getenv('CONTENTFUL_PHP_MANAGEMENT_SPACE_ID_READ_WRITE');
        $this->codeGeneratorSpaceId = \getenv('CONTENTFUL_PHP_MANAGEMENT_SPACE_ID_CODE_GENERATOR');
        $this->organizationId = \getenv('CONTENTFUL_PHP_MANAGEMENT_ORGANIZATION_ID');
    }

    protected function getClient(): Client
    {
        $host = \getenv('CONTENTFUL_PHP_MANAGEMENT_SDK_HOST');
        $options = $host
            ? ['host' => $host]
            : [];

        return new Client($this->token, $options);
    }

    protected function getReadOnlySpaceProxy(): SpaceProxy
    {
        return $this->getClient()
            ->getSpaceProxy($this->readOnlySpaceId)
        ;
    }

    protected function getReadOnlyEnvironmentProxy(): EnvironmentProxy
    {
        return $this->getReadOnlySpaceProxy()
            ->getEnvironmentProxy('master')
        ;
    }

    protected function getReadWriteSpaceProxy(): SpaceProxy
    {
        return $this->getClient()
            ->getSpaceProxy($this->readWriteSpaceId)
        ;
    }

    protected function getReadWriteEnvironmentProxy(): EnvironmentProxy
    {
        return $this->getReadWriteSpaceProxy()
            ->getEnvironmentProxy('master')
        ;
    }

    protected function getCodeGeneratorProxy(): EnvironmentProxy
    {
        return $this->getClient()->getEnvironmentProxy($this->codeGeneratorSpaceId, 'master');
    }

    /**
     * @param object $object
     */
    protected function assertJsonFixtureEqualsJsonObject(string $file, $object, string $message = '')
    {
        $this->assertJsonStringEqualsJsonFile(
            __DIR__.'/Fixtures/'.$file,
            guzzle_json_encode($object, \JSON_UNESCAPED_UNICODE | \JSON_PRETTY_PRINT),
            $message
        );
    }

    protected function assertJsonStructuresAreEqual($expected, $object, string $message = '')
    {
        $this->assertJsonStringEqualsJsonString(
            guzzle_json_encode($expected, \JSON_UNESCAPED_UNICODE | \JSON_PRETTY_PRINT),
            guzzle_json_encode($object, \JSON_UNESCAPED_UNICODE | \JSON_PRETTY_PRINT),
            $message
        );
    }
}
