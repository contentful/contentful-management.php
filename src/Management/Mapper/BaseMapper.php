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
use Contentful\Management\ResourceBuilder;
use Contentful\ResourceArray;

/**
 * BaseMapper class.
 */
abstract class BaseMapper implements MapperInterface
{
    /**
     * @var \Closure[]
     */
    private static $hydrators = [];

    /**
     * @var ResourceBuilder
     */
    protected $builder;

    /**
     * BaseMapper constructor.
     *
     * @param ResourceBuilder $builder
     */
    public function __construct(ResourceBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param string|object $target either a FQCN, or an object whose class will be automatically inferred
     * @param array         $data
     *
     * @return ResourceInterface|ResourceArray
     */
    protected function hydrate($target, array $data)
    {
        $class = \is_object($target) ? \get_class($target) : $target;
        if (\is_string($target)) {
            $target = (new \ReflectionClass($class))
                ->newInstanceWithoutConstructor();
        }

        $hydrator = $this->getHydrator($class);

        $hydrator($target, $data);

        return $target;
    }

    /**
     * @param string $class
     *
     * @return \Closure
     */
    private function getHydrator(string $class): \Closure
    {
        if (isset(self::$hydrators[$class])) {
            return self::$hydrators[$class];
        }

        return self::$hydrators[$class] = \Closure::bind(function ($object, $properties) {
            foreach ($properties as $property => $value) {
                $object->$property = $value;
            }
        }, null, $class);
    }
}
