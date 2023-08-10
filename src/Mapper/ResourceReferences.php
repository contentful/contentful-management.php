<?php

declare(strict_types=1);

namespace Contentful\Management\Mapper;

use Contentful\Management\Resource\ResourceReferences as ResourceClass;

/**
 * ResourceArray class.
 *
 * This class is responsible for converting raw API data into a PHP object
 * of class Contentful\Core\Resource\ResourceArray.
 */
class ResourceReferences extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        $includes = [];
        foreach ($data['includes'] as $referenceGroupKey => $referencesGroupItems) {
          $includes[$referenceGroupKey] = \array_map(function ($item) {
              return $this->builder->build($item);
          }, $referencesGroupItems);
        }
        return new ResourceClass(
            \array_map(function ($item) {
                return $this->builder->build($item);
            }, $data['items']),
            $includes,
        );
    }
}
