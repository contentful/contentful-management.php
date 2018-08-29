<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Mapper;

use Contentful\Core\Api\Link;
use Contentful\Management\Resource\DeliveryApiKey as ResourceClass;
use Contentful\Management\SystemProperties;

/**
 * DeliveryApiKey class.
 *
 * This class is responsible for converting raw API data into a PHP object
 * of class Contentful\Management\Resource\DeliveryApiKey.
 */
class DeliveryApiKey extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        return $this->hydrate($resource ?: ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'name' => $data['name'],
            'accessToken' => $data['accessToken'],
            'description' => $data['description'],
            'environments' => $this->buildEnvironments($data['environments'] ?? []),
            'previewApiKey' => isset($data['preview_api_key'])
                ? new Link($data['preview_api_key']['sys']['id'], 'PreviewApiKey')
                : null,
        ]);
    }

    /**
     * @param array $environments
     *
     * @return Link[]
     */
    private function buildEnvironments(array $environments): array
    {
        if (!$environments) {
            return [new Link('master', 'Environment')];
        }

        $environmentLinks = [];
        foreach ($environments as $environment) {
            $environmentLinks[] = new Link($environment['sys']['id'], 'Environment');
        }

        return $environmentLinks;
    }
}
