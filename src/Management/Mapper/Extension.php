<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Mapper;

use Contentful\Management\Resource\Extension as ResourceClass;
use Contentful\Management\Resource\Extension\FieldType;
use Contentful\Management\SystemProperties;

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
        return $this->hydrate($resource ?: ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'name' => $data['extension']['name'],
            'source' => $data['extension']['src'] ?? $data['extension']['srcdoc'] ?? '',
            'fieldTypes' => array_map([$this, 'buildFieldTypes'], $data['extension']['fieldTypes']),
            'sidebar' => $data['extension']['sidebar'],
        ]);
    }

    /**
     * @param array $data
     *
     * @return FieldType
     */
    protected function buildFieldTypes(array $data): FieldType
    {
        $secondParam = [];

        if ($data['type'] == 'Link') {
            $secondParam = [$data['linkType']];
        }

        if ($data['type'] == 'Array') {
            $secondParam = [
                $data['items']['type'],
                $data['items']['linkType'] ?? null,
            ];
        }

        return new FieldType(
            $data['type'],
            $secondParam
        );
    }
}
