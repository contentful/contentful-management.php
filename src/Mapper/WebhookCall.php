<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Mapper;

use Contentful\Core\Api\DateTimeImmutable;
use Contentful\Management\Resource\WebhookCall as ResourceClass;
use Contentful\Management\SystemProperties\WebhookCall as SystemProperties;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * WebhookCall class.
 *
 * This class is responsible for converting raw API data into a PHP object
 * of class Contentful\Management\Resource\WebhookCall.
 */
class WebhookCall extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        if (\null !== $resource) {
            throw new \LogicException(\sprintf(
                'Trying to update resource object in mapper of type "%s", but only creation from scratch is supported.',
                static::class
            ));
        }

        /** @var ResourceClass $webhookCall */
        $webhookCall = $this->hydrator->hydrate(ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'request' => isset($data['request']) ? new Request(
                $data['request']['method'],
                $data['request']['url'],
                $data['request']['headers'],
                $data['request']['body']
            ) : \null,
            'response' => isset($data['response']) ? new Response(
                $data['response']['statusCode'],
                $data['response']['headers'],
                $data['response']['body']
            ) : \null,
            'statusCode' => $data['statusCode'],
            'eventType' => $data['eventType'],
            'error' => $data['errors'] ? $data['errors'][0] : \null,
            'url' => $data['url'],
            'requestAt' => new DateTimeImmutable($data['requestAt']),
            'responseAt' => new DateTimeImmutable($data['responseAt']),
        ]);

        return $webhookCall;
    }
}
