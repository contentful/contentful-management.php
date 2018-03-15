<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
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
    protected $defaultSpaceId = '34luz0flcmxt';

    /**
     * @var string
     */
    protected $codeGenerationSpaceId = 't7rprcaoexcq';

    /**
     * @var string
     */
    protected $testOrganizationId = '4Q3Lza73mxcjmluLU7V5EG';

    public function setUp()
    {
        $this->token = \getenv('CONTENTFUL_CMA_TEST_TOKEN');
    }

    /**
     * @param string|null $spaceId
     *
     * @return Client
     */
    protected function getClient(): Client
    {
        return new Client($this->token);
    }

    protected function getDefaultSpaceProxy(): SpaceProxy
    {
        return $this->getClient()->getSpaceProxy($this->defaultSpaceId);
    }

    protected function getDefaultEnvironmentProxy(): EnvironmentProxy
    {
        return $this->getClient()->getEnvironmentProxy($this->defaultSpaceId, 'master');
    }

    protected function getCodeGenerationProxy(): EnvironmentProxy
    {
        return $this->getClient()->getEnvironmentProxy($this->codeGenerationSpaceId, 'master');
    }

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
        $this->assertJsonStringEqualsJsonFile(__DIR__.'/Fixtures/'.$file, guzzle_json_encode($object, \JSON_UNESCAPED_UNICODE), $message);
    }

    /**
     * Creates an empty assertion (true == true).
     * This is done to mark tests that are expected to simply work (i.e. not throw exceptions).
     * As PHPUnit does not provide convenience methods for marking a test as passed,
     * we define one.
     */
    protected function markTestAsPassed()
    {
        $this->assertTrue(true, 'Test case did not throw an exception and passed.');
    }
}
