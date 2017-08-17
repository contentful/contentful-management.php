<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Mapper;

use Contentful\Management\Resource\WebhookCallDetails as ResourceClass;
use Contentful\Management\SystemProperties;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * WebhookCallDetails class.
 */
class WebhookCallDetails extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        if ($resource !== null) {
            throw new \LogicException(sprintf(
                'Trying to update resource %s, which only supports creation',
                static::class
            ));
        }

        return $this->hydrate(ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'request' => new Request(
                $data['request']['method'],
                $data['request']['url'],
                $data['request']['headers'],
                $data['request']['body']
            ),
            'response' => new Response(
                $data['response']['statusCode'],
                $data['response']['headers'],
                $data['response']['body']
            ),
            'statusCode' => $data['statusCode'],
            'eventType' => $data['eventType'],
            'url' => $data['url'],
            'error' => $data['errors'] ? $data['errors'][0] : null,
            'requestAt' => new \DateTimeImmutable($data['requestAt']),
            'responseAt' => new \DateTimeImmutable($data['responseAt']),
        ]);
    }
}
