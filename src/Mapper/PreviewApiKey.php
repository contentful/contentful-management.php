<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Mapper;

use Contentful\Core\Api\Link;
use Contentful\Management\Resource\PreviewApiKey as ResourceClass;
use Contentful\Management\SystemProperties\ApiKey as SystemProperties;

/**
 * PreviewApiKey class.
 *
 * This class is responsible for converting raw API data into a PHP object
 * of class Contentful\Management\Resource\PreviewApiKey.
 */
class PreviewApiKey extends BaseMapper
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

        /** @var ResourceClass $previewApiKey */
        $previewApiKey = $this->hydrator->hydrate(ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'name' => $data['name'],
            'accessToken' => $data['accessToken'],
            'description' => $data['description'],
            'environments' => $this->buildEnvironments($data['environments'] ?? []),
        ]);

        return $previewApiKey;
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
