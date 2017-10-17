<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Mapper\ContentType\Field;

use Contentful\Management\Resource\ContentType\Field\ArrayField as ResourceClass;
use Contentful\Management\Resource\ContentType\Field\FieldInterface;

/**
 * ArrayField class.
 */
class ArrayField extends BaseField
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): FieldInterface
    {
        return $this->hydrate(ResourceClass::class, [
            'id' => $data['id'],
            'name' => $data['name'],
            'required' => $data['required'] ?? null,
            'localized' => $data['localized'] ?? null,
            'disabled' => $data['disabled'] ?? null,
            'omitted' => $data['omitted'] ?? null,
            'validations' => isset($data['validations'])
                ? \array_map([$this, 'mapValidation'], $data['validations'])
                : [],
            'itemsType' => $data['items']['type'],
            'itemsLinkType' => $data['items']['linkType'] ?? null,
            'itemsValidations' => isset($data['items']['validations'])
                ? \array_map([$this, 'mapValidation'], $data['items']['validations'])
                : [],
        ]);
    }
}
