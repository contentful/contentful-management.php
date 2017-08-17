<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Mapper;

use Contentful\ResourceArray as ResourceClass;

/**
 * ResourceArray class.
 */
class ResourceArray extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        return new ResourceClass(
            array_map(function ($item) {
                return $this->builder->build($item);
            }, $data['items']),
            $data['total'] ?? 0,
            $data['limit'],
            $data['skip']
        );
    }
}
