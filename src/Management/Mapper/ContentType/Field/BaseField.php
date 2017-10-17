<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Mapper\ContentType\Field;

use Contentful\Management\Mapper\BaseMapper;
use Contentful\Management\Resource\ContentType\Field\BooleanField;
use Contentful\Management\Resource\ContentType\Field\DateField;
use Contentful\Management\Resource\ContentType\Field\FieldInterface;
use Contentful\Management\Resource\ContentType\Field\IntegerField;
use Contentful\Management\Resource\ContentType\Field\LocationField;
use Contentful\Management\Resource\ContentType\Field\NumberField;
use Contentful\Management\Resource\ContentType\Field\ObjectField;
use Contentful\Management\Resource\ContentType\Field\SymbolField;
use Contentful\Management\Resource\ContentType\Field\TextField;
use Contentful\Management\Resource\ContentType\Validation\ValidationInterface;

/**
 * BaseField class.
 */
abstract class BaseField extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): FieldInterface
    {
        $type = $data['type'];
        $fieldTypes = [
            'Boolean' => BooleanField::class,
            'Date' => DateField::class,
            'Integer' => IntegerField::class,
            'Location' => LocationField::class,
            'Number' => NumberField::class,
            'Object' => ObjectField::class,
            'Symbol' => SymbolField::class,
            'Text' => TextField::class,
        ];

        return $this->hydrate($fieldTypes[$type], [
            'id' => $data['id'],
            'name' => $data['name'],
            'required' => $data['required'] ?? null,
            'localized' => $data['localized'] ?? null,
            'disabled' => $data['disabled'] ?? null,
            'omitted' => $data['omitted'] ?? null,
            'validations' => isset($data['validations'])
                ? \array_map([$this, 'mapValidation'], $data['validations'])
                : [],
        ]);
    }

    /**
     * @param array $data
     *
     * @return ValidationInterface
     */
    protected function mapValidation(array $data): ValidationInterface
    {
        $fqcn = '\\Contentful\\Management\\Mapper\\ContentType\\Validation\\'.\ucfirst(\array_keys($data)[0]).'Validation';

        return $this->builder->getMapper($fqcn)
            ->map(null, $data);
    }
}
