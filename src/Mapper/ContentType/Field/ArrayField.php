<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
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
        return (new ResourceClass(
            $data['id'],
            $data['name'],
            $data['items']['type'],
            $data['items']['linkType'] ?? null
        ))->setRequired($data['required'] ?? false)
            ->setLocalized($data['localized'] ?? false)
            ->setDisabled($data['disabled'] ?? false)
            ->setOmitted($data['omitted'] ?? false)
            ->setValidations(isset($data['validations'])
                ? \array_map([$this, 'mapValidation'], $data['validations'])
                : [])
            ->setItemsValidations(isset($data['items']['validations'])
                ? \array_map([$this, 'mapValidation'], $data['items']['validations'])
                : [])
            ;
    }
}
