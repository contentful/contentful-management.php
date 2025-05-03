<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
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
            ->getConfigFor(Upload::class)
        ;

        $this->assertSame([
            'class' => Upload::class,
            'uri' => '/spaces/{space}/uploads/{upload}',
            'host' => 'https://upload.contentful.com',
            'parameters' => ['space'],
            'id' => 'upload',
        ], $config);
    }

    public function testGetFromStringWithoutInitialBackslash()
    {
        $config = (new ApiConfiguration())
            ->getConfigFor('\\Contentful\\Management\\Resource\\PersonalAccessToken')
        ;

        $this->assertSame([
            'class' => 'Contentful\\Management\\Resource\\PersonalAccessToken',
            'uri' => 'users/me/access_tokens/{personalAccessToken}',
            'parameters' => [],
            'id' => 'personalAccessToken',
        ], $config);
    }

    public function testGetFromBaseObject()
    {
        $config = (new ApiConfiguration())
            ->getConfigFor(new Asset())
        ;

        $this->assertSame([
            'class' => Asset::class,
            'uri' => '/spaces/{space}/environments/{environment}/assets/{asset}',
            'parameters' => ['space', 'environment'],
            'id' => 'asset',
        ], $config);
    }

    public function testGetFromExtendedObject()
    {
        $config = (new ApiConfiguration())
            ->getConfigFor(new ExtendedResource('contentType'))
        ;

        $this->assertSame([
            'class' => ExtendedResource::class,
            'uri' => '/spaces/{space}/environments/{environment}/entries/{entry}',
            'parameters' => ['space', 'environment'],
            'id' => 'entry',
        ], $config);
    }

    public function testThrowOnInvalidResourceObject()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Trying to access invalid configuration for class "stdClass".');

        (new ApiConfiguration())
            ->getConfigFor(\stdClass::class)
        ;
    }

    public function testGetLinkConfiguration()
    {
        $config = (new ApiConfiguration())
            ->getLinkConfigFor('WebhookDefinition')
        ;

        $this->assertSame([
            'class' => 'Contentful\\Management\\Resource\\Webhook',
            'uri' => '/spaces/{space}/webhook_definitions/{webhook}',
            'parameters' => ['space'],
            'id' => 'webhook',
            'class' => 'Contentful\Management\\Resource\\Webhook',
        ], $config);
    }

    public function testGetConfigForInvalidLinkType()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Trying to get link configuration for an invalid link type "Invalid".');

        (new ApiConfiguration())
            ->getLinkConfigFor('Invalid')
        ;
    }
}

class ExtendedResource extends Entry
{
}
