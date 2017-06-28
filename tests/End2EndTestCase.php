<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests;

use Contentful\Management\Client;
use Contentful\Management\SpaceManager;

class End2EndTestCase extends \PHPUnit_Framework_TestCase
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
}
