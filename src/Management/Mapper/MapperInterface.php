<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Mapper;

use Contentful\Management\Resource\ResourceInterface;
use Contentful\ResourceArray;

/**
 * MapperInterface.
 *
 * This interface must be implemented by those classes that are capable
 * of transforming a raw array of data fetched from the API to an actual
 * PHP object.
 */
interface MapperInterface
{
    /**
     * Maps the given data to a resource.
     *
     * ATTENTION: This will directly modify the given resource.
     * If $resource is "null", the method is expected to create
     * a new instance of the appropriate class.
     *
     * @param ResourceInterface|null $resource
     * @param array                  $data
     *
     * @return ResourceInterface|ResourceArray
     */
    public function map($resource, array $data);
}
