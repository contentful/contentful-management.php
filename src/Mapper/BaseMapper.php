<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Mapper;

use Contentful\Core\ResourceBuilder\MapperInterface;
use Contentful\Core\ResourceBuilder\ObjectHydrator;
use Contentful\Management\ResourceBuilder;

/**
 * BaseMapper class.
 */
abstract class BaseMapper implements MapperInterface
{
    /**
     * @var ResourceBuilder
     */
    protected $builder;

    /**
     * @var ObjectHydrator
     */
    protected $hydrator;

    /**
     * BaseMapper constructor.
     *
     * @param ResourceBuilder $builder
     */
    public function __construct(ResourceBuilder $builder)
    {
        $this->builder = $builder;
        $this->hydrator = new ObjectHydrator();
    }
}
