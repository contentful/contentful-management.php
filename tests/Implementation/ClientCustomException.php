<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2021 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Implementation;

use Contentful\Core\Resource\ResourceInterface;
use Contentful\Management\Client;

class ClientCustomException extends Client
{
    public function __construct(string $accessToken, $httpClient, array $options = [])
    {
        if ($httpClient) {
            $options['guzzle'] = $httpClient;
        }
        parent::__construct($accessToken, $options);
    }

    public function request(string $method, string $uri, array $options = []): ResourceInterface
    {
        return parent::request($method, $uri, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function callApi(string $method, string $path, array $options = []): array
    {
        return parent::callApi($method, $path, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getApi(): string
    {
        return 'MANAGEMENT';
    }

    /**
     * {@inheritdoc}
     */
    protected static function getPackageName(): string
    {
        return 'contentful/core';
    }

    /**
     * {@inheritdoc}
     */
    protected static function getSdkName(): string
    {
        return 'contentful-core.php';
    }

    /**
     * {@inheritdoc}
     */
    protected static function getApiContentType(): string
    {
        return 'application/vnd.contentful.management.v1+json';
    }

    /**
     * {@inheritdoc}
     */
    protected function getExceptionNamespace(): string
    {
        return __NAMESPACE__.'\\Exception';
    }
}
