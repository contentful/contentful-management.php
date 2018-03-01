<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Mapper\ContentType\Field;

use Contentful\Management\Resource\ContentType\Field\FieldInterface;
use Contentful\Management\Resource\ContentType\Field\LinkField as ResourceClass;

/**
 * LinkField class.
 */
class LinkField extends BaseField
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
            'linkType' => $data['linkType'],
        ]);
    }
}
