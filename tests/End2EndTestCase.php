<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests;

use Contentful\Management\Client;
use Contentful\Management\SpaceManager;
use PHPUnit\Framework\TestCase;

class End2EndTestCase extends TestCase
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $readOnlySpaceId = 'cfexampleapi';

    /**
     * @var string
     */
    protected $readWriteSpaceId = '34luz0flcmxt';

    /**
     * @var string
     */
    protected $testOrganizationId = '4Q3Lza73mxcjmluLU7V5EG';

    public function setUp()
    {
        $this->token = getenv('CONTENTFUL_CMA_TEST_TOKEN');
        $this->client = new Client($this->token);
    }

    /**
     * @return SpaceManager
     */
    protected function getReadOnlySpaceManager(): SpaceManager
    {
        return $this->client->getSpaceManager($this->readOnlySpaceId);
    }

    /**
     * @return SpaceManager
     */
    protected function getReadWriteSpaceManager(): SpaceManager
    {
        return $this->client->getSpaceManager($this->readWriteSpaceId);
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
