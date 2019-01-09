<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Mapper;

use Contentful\Core\Resource\ResourceArray as ResourceClass;

/**
 * ResourceArray class.
 *
 * This class is responsible for converting raw API data into a PHP object
 * of class Contentful\Core\Resource\ResourceArray.
 */
class ResourceArray extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        return new ResourceClass(
            \array_map(function ($item) {
                return $this->builder->build($item);
            }, $data['items']),
            $data['total'] ?? 0,
            $data['limit'],
            $data['skip']
        );
    }
}
