<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\Unit;

use Contentful\Management\ApiConfiguration;
use Contentful\Management\Resource\Asset;
use Contentful\Management\Resource\Entry;
use Contentful\Management\Resource\Upload;
use Contentful\Tests\Management\BaseTestCase;

class ApiConfigurationTest extends BaseTestCase
{
    public function testGetFromString()
    {
        $config = (new ApiConfiguration())
            ->getConfigFor(Upload::class);

        $this->assertSame([
            'uri' => '/spaces/{space}/uploads/{upload}',
            'baseUri' => 'https://upload.contentful.com',
            'parameters' => ['space'],
            'id' => 'upload',
        ], $config);
    }

    public function testGetFromStringWithoutInitialBackslash()
    {
        $config = (new ApiConfiguration())
            ->getConfigFor('\\Contentful\\Management\\Resource\\PersonalAccessToken');

        $this->assertSame([
            'uri' => 'users/me/access_tokens/{personalAccessToken}',
            'parameters' => [],
            'id' => 'personalAccessToken',
        ], $config);
    }

    public function testGetFromBaseObject()
    {
        $config = (new ApiConfiguration())
            ->getConfigFor(new Asset());

        $this->assertSame([
            'uri' => '/spaces/{space}/assets/{asset}',
            'parameters' => ['space'],
            'id' => 'asset',
        ], $config);
    }

    public function testGetFromExtendedObject()
    {
        $entry = new class('contentType') extends Entry {
        };

        $config = (new ApiConfiguration())
            ->getConfigFor($entry);

        $this->assertSame([
            'uri' => '/spaces/{space}/entries/{entry}',
            'parameters' => ['space'],
            'id' => 'entry',
        ], $config);
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Trying to get configuration for an object of class "stdClass" which does not implement ResourceInterface.
     */
    public function testThrowOnInvalidObject()
    {
        (new ApiConfiguration())
            ->getConfigFor(new \stdClass());
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Trying to access invalid configuration for class "stdClass".
     */
    public function testThrowOnInvalidResourceObject()
    {
        (new ApiConfiguration())
            ->getConfigFor(\stdClass::class);
    }
}
