<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Mapper;

use Contentful\Management\Resource\Webhook as ResourceClass;
use Contentful\Management\SystemProperties;

/**
 * Webhook class.
 *
 * This class is responsible for converting raw API data into a PHP object
 * of class Contentful\Management\Resource\Webhook.
 */
class Webhook extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        // The API never returns the password in the response.
        // This means that the object that the user requested will have its "httpBasicPassword" field set to null.
        // It's a destructive behavior, but it's consinstent with the way the API works.
        return $this->hydrate($resource ?: ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'name' => $data['name'],
            'url' => $data['url'],
            'httpBasicUsername' => $data['httpBasicUsername'] ?? null,
            'httpBasicPassword' => null,
            'topics' => $data['topics'],
            'headers' => $this->formatHeaders($data['headers']),
        ], $resource);
    }

    /**
     * @param array $dataHeaders
     *
     * @return string[]
     */
    protected function formatHeaders(array $dataHeaders): array
    {
        $headers = [];
        foreach ($dataHeaders as $header) {
            $headers[$header['key']] = $header['value'];
        }

        return $headers;
    }
}
