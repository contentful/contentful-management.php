<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Mapper;

use Contentful\Management\Resource\WebhookHealth as ResourceClass;
use Contentful\Management\SystemProperties\WebhookHealth as SystemProperties;

/**
 * WebhookHealth class.
 *
 * This class is responsible for converting raw API data into a PHP object
 * of class Contentful\Management\Resource\WebhookHealth.
 */
class WebhookHealth extends BaseMapper
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

        /** @var ResourceClass $webhookHealth */
        $webhookHealth = $this->hydrator->hydrate(ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'total' => $data['calls']['total'],
            'healthy' => $data['calls']['healthy'],
        ]);

        return $webhookHealth;
    }
}
