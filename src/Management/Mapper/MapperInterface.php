<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Mapper;

use Contentful\Management\Resource\ResourceInterface;

/**
 * MapperInterface.
 */
interface MapperInterface
{
    /**
     * Maps the given data to a resource.
     *
     * ATTENTION: This will directly modify the given resource.
     * If $resource is `null`, the method is expected to create
     * a new instance of the appropriate class.
     *
     * @param ResourceInterface|null $resource
     * @param array                  $data
     *
     * @return mixed
     */
    public function map($resource, array $data);
}
