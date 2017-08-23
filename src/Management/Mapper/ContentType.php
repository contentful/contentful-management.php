<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Mapper;

use Contentful\Management\Resource\ContentType\Field;
use Contentful\Management\Resource\ContentType\Validation;
use Contentful\Management\Resource\ContentType as ResourceClass;
use Contentful\Management\SystemProperties;

/**
 * ContentType class.
 */
class ContentType extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        return $this->hydrate($resource ?: ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'displayField' => $data['displayField'] ?? null,
            'fields' => array_map([$this, 'buildContentTypeField'], $data['fields']),
            'isPublished' => isset($data['sys']['revision']),
        ]);
    }

    /**
     * @param array $data
     *
     * @return Field\FieldInterface
     */
    protected function buildContentTypeField(array $data): Field\FieldInterface
    {
        $fieldTypes = [
            'Array' => Field\ArrayField::class,
            'Boolean' => Field\BooleanField::class,
            'Date' => Field\DateField::class,
            'Integer' => Field\IntegerField::class,
            'Link' => Field\LinkField::class,
            'Location' => Field\LocationField::class,
            'Number' => Field\NumberField::class,
            'Object' => Field\ObjectField::class,
            'Symbol' => Field\SymbolField::class,
            'Text' => Field\TextField::class,
        ];

        $type = $data['type'];

        $hydratorData = [
            'id' => $data['id'],
            'name' => $data['name'],
            'required' => $data['required'] ?? null,
            'localized' => $data['localized'] ?? null,
            'disabled' => $data['disabled'] ?? null,
            'omitted' => $data['omitted'] ?? null,
            'validations' => isset($data['validations']) ? array_map([$this, 'buildFieldValidation'], $data['validations']) : null,
        ];

        if ($type === 'Link') {
            $hydratorData['linkType'] = $data['linkType'];
        }

        if ($type === 'Array') {
            $items = $data['items'];
            $hydratorData['itemsType'] = $items['type'];
            $hydratorData['itemsLinkType'] = $items['linkType'] ?? null;
            $hydratorData['itemsValidations'] = isset($items['validations']) ? array_map([$this, 'buildFieldValidation'], $items['validations']) : null;
        }

        return $this->hydrate($fieldTypes[$type], $hydratorData);
    }

    /**
     * @param array $data
     *
     * @return Validation\ValidationInterface
     */
    protected function buildFieldValidation(array $data): Validation\ValidationInterface
    {
        $validations = [
            'size' => Validation\SizeValidation::class,
            'in' => Validation\InValidation::class,
            'linkContentType' => Validation\LinkContentTypeValidation::class,
            'linkMimetypeGroup' => Validation\LinkMimetypeGroupValidation::class,
            'range' => Validation\RangeValidation::class,
            'regexp' => Validation\RegexpValidation::class,
            'unique' => Validation\UniqueValidation::class,
            'dateRange' => Validation\DateRangeValidation::class,
            'assetImageDimensions' => Validation\AssetImageDimensionsValidation::class,
            'assetFileSize' => Validation\AssetFileSizeValidation::class,
        ];

        $type = array_keys($data)[0];
        $class = $validations[$type];

        return $class::fromApiResponse($data);
    }
}
