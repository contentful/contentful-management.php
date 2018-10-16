<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management;

use Contentful\Core\Api\Link;
use Contentful\Management\Client;
use Contentful\Management\Proxy\EnvironmentProxy;
use Contentful\Management\Proxy\SpaceProxy;
use PHPUnit\Framework\TestCase;
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
    protected $codeGenerationSpaceId;

    /**
     * @var string
     */
    protected $organizationId;

    public function setUp()
    {
        $this->token = \getenv('CONTENTFUL_PHP_MANAGEMENT_TEST_TOKEN');

        $this->readOnlySpaceId = \getenv('CONTENTFUL_PHP_MANAGEMENT_SPACE_ID_READ_ONLY');
        $this->readWriteSpaceId = \getenv('CONTENTFUL_PHP_MANAGEMENT_SPACE_ID_READ_WRITE');
        $this->codeGenerationSpaceId = \getenv('CONTENTFUL_PHP_MANAGEMENT_SPACE_ID_CODE_GENERATION');
        $this->organizationId = \getenv('CONTENTFUL_PHP_MANAGEMENT_ORGANIZATION_ID');
    }

    /**
     * @return Client
     */
    protected function getClient(): Client
    {
        $host = \getenv('CONTENTFUL_PHP_MANAGEMENT_SDK_HOST');
        $options = $host
            ? ['host' => $host]
            : [];

        return new Client($this->token, $options);
    }

    /**
     * @return SpaceProxy
     */
    protected function getReadOnlySpaceProxy(): SpaceProxy
    {
        return $this->getClient()
            ->getSpaceProxy($this->readOnlySpaceId)
        ;
    }

    /**
     * @return EnvironmentProxy
     */
    protected function getReadOnlyEnvironmentProxy(): EnvironmentProxy
    {
        return $this->getReadOnlySpaceProxy()
            ->getEnvironmentProxy('master')
        ;
    }

    /**
     * @return SpaceProxy
     */
    protected function getReadWriteSpaceProxy(): SpaceProxy
    {
        return $this->getClient()
            ->getSpaceProxy($this->readWriteSpaceId)
        ;
    }

    /**
     * @return EnvironmentProxy
     */
    protected function getReadWriteEnvironmentProxy(): EnvironmentProxy
    {
        return $this->getReadWriteSpaceProxy()
            ->getEnvironmentProxy('master')
        ;
    }

    /**
     * @return EnvironmentProxy
     */
    protected function getCodeGenerationProxy(): EnvironmentProxy
    {
        return $this->getClient()->getEnvironmentProxy($this->codeGenerationSpaceId, 'master');
    }

    /**
     * Convenience method for performing assertions on link objects.
     *
     * @param        $id
     * @param        $linkType
     * @param Link   $link
     * @param string $message
     */
    protected function assertLink($id, $linkType, Link $link, $message = '')
    {
        $this->assertSame($id, $link->getId(), $message);
        $this->assertSame($linkType, $link->getLinkType(), $message);
    }

    /**
     * @param string $file
     * @param object $object
     * @param string $message
     */
    protected function assertJsonFixtureEqualsJsonObject(string $file, $object, string $message = '')
    {
        $this->assertJsonStringEqualsJsonFile(
            __DIR__.'/Fixtures/'.$file,
            guzzle_json_encode($object, \JSON_UNESCAPED_UNICODE),
            $message
        );
    }

    /**
     * Creates an empty assertion (true == true).
     * This is done to mark tests that are expected to simply work (i.e. not throw exceptions).
     * As PHPUnit does not provide convenience methods for marking a test as passed,
     * we define one.
     */
    protected function markTestAsPassed()
    {
        $this->assertTrue(\true, 'Test case did not throw an exception and passed.');
    }
}
