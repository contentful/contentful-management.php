<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Mapper;

use Contentful\Management\Resource\Webhook as ResourceClass;
use Contentful\Management\Resource\Webhook\EqualityFilter;
use Contentful\Management\Resource\Webhook\FilterInterface;
use Contentful\Management\Resource\Webhook\InclusionFilter;
use Contentful\Management\Resource\Webhook\NotFilter;
use Contentful\Management\Resource\Webhook\RegexpFilter;
use Contentful\Management\SystemProperties\Webhook as SystemProperties;

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
        // It's a destructive behavior, but it's consistent with the way the API works.
        /** @var ResourceClass $webhook */
        $webhook = $this->hydrator->hydrate($resource ?: ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'name' => $data['name'],
            'url' => $data['url'],
            'httpBasicUsername' => $data['httpBasicUsername'] ?? \null,
            'httpBasicPassword' => \null,
            'topics' => $data['topics'],
            'headers' => $this->formatHeaders($data['headers']),
            'transformation' => $data['transformation'] ?? [],
            'filters' => \array_map([$this, 'formatFilter'], $data['filters'] ?? []),
        ]);

        return $webhook;
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

    /**
     * @param array $data
     *
     * @return FilterInterface
     */
    protected function formatFilter(array $data): FilterInterface
    {
        \reset($data);
        $key = \key($data);

        switch ($key) {
            case 'equals':
                $docKey = isset($data[$key][0]['doc']) ? 0 : 1;
                $valueKey = 1 - $docKey;

                return new EqualityFilter(
                    $data[$key][$docKey]['doc'],
                    $data[$key][$valueKey]
                );
            case 'in':
                $docKey = isset($data[$key][0]['doc']) ? 0 : 1;
                $valueKey = 1 - $docKey;

                return new InclusionFilter(
                    $data[$key][$docKey]['doc'],
                    $data[$key][$valueKey]
                );
            case 'not':
                return new NotFilter(
                    $this->formatFilter($data[$key])
                );
            case 'regexp':
                $docKey = isset($data[$key][0]['doc']) ? 0 : 1;
                $valueKey = 1 - $docKey;

                return new RegexpFilter(
                    $data[$key][$docKey]['doc'],
                    $data[$key][$valueKey]['pattern']
                );
            default:
                throw new \RuntimeException(\sprintf(
                    'Trying to build a filter object using invalid key "%s".',
                    $key
                ));
        }
    }
}
