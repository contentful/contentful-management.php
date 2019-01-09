<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Mapper;

use Contentful\Management\Resource\Extension as ResourceClass;
use Contentful\Management\Resource\Extension\FieldType;
use Contentful\Management\Resource\Extension\Parameter;
use Contentful\Management\SystemProperties\Extension as SystemProperties;

/**
 * Extension class.
 *
 * This class is responsible for converting raw API data into a PHP object
 * of class Contentful\Management\Resource\Extension.
 */
class Extension extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        /** @var ResourceClass $extension */
        $extension = $this->hydrator->hydrate($resource ?: ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'name' => $data['extension']['name'],
            'source' => $data['extension']['src'] ?? $data['extension']['srcdoc'] ?? '',
            'fieldTypes' => \array_map([$this, 'buildFieldTypes'], $data['extension']['fieldTypes']),
            'sidebar' => $data['extension']['sidebar'],
            'installationParameters' => \array_map(
                [$this, 'buildParameter'],
                $data['extension']['parameters']['installation'] ?? []
            ),
            'instanceParameters' => \array_map(
                [$this, 'buildParameter'],
                $data['extension']['parameters']['instance'] ?? []
            ),
        ]);

        return $extension;
    }

    /**
     * @param array $data
     *
     * @return FieldType
     */
    protected function buildFieldTypes(array $data): FieldType
    {
        $secondParam = [];

        if ('Link' === $data['type']) {
            $secondParam = [$data['linkType']];
        }

        if ('Array' === $data['type']) {
            $secondParam = [
                $data['items']['type'],
                $data['items']['linkType'] ?? \null,
            ];
        }

        return new FieldType(
            $data['type'],
            $secondParam
        );
    }

    /**
     * @param array $data
     *
     * @return Parameter
     */
    protected function buildParameter(array $data): Parameter
    {
        return (new Parameter($data['id'], $data['name'], $data['type']))
            ->setDescription($data['description'] ?? \null)
            ->setRequired($data['required'] ?? \false)
            ->setDefault($data['default'] ?? \null)
            ->setOptions($data['options'] ?? [])
            ->setLabels($data['labels'] ?? [])
        ;
    }
}
