<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management;

use Contentful\Management\Client;
use PHPUnit\Framework\TestCase;

class End2EndTestCase extends TestCase
{
    /**
     * @var string
     */
    protected $token;

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
        $this->token = \getenv('CONTENTFUL_CMA_TEST_TOKEN');
    }

    /**
     * @param string|null $spaceId
     *
     * @return Client
     */
    protected function getClient(string $spaceId = null): Client
    {
        return new Client($this->token, $spaceId);
    }

    /**
     * @return Client
     */
    protected function getUnboundClient(): Client
    {
        return $this->getClient(null);
    }

    /**
     * @return Client
     */
    protected function getReadOnlyClient(): Client
    {
        return $this->getClient($this->readOnlySpaceId);
    }

    /**
     * @return Client
     */
    protected function getReadWriteClient(): Client
    {
        return $this->getClient($this->readWriteSpaceId);
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
