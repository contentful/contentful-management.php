<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
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

    public function callApi(string $method, string $path, array $options = []): array
    {
        return parent::callApi($method, $path, $options);
    }

    public function getApi(): string
    {
        return 'MANAGEMENT';
    }

    protected static function getPackageName(): string
    {
        return 'contentful/core';
    }

    protected static function getSdkName(): string
    {
        return 'contentful-core.php';
    }

    protected static function getApiContentType(): string
    {
        return 'application/vnd.contentful.management.v1+json';
    }

    protected function getExceptionNamespace(): string
    {
        return __NAMESPACE__.'\\Exception';
    }
}
