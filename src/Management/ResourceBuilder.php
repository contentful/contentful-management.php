<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

use Contentful\ResourceArray;

class ResourceBuilder
{
    /**
     * @var \Closure[]
     */
    private static $hydratorCache = [];

    /**
     * ResourceBuilder constructor.
     *
     * Empty constructor for forward compatibility.
     */
    public function __construct()
    {
    }

    /**
     * @param  array $data
     * @return Space|Locale|ResourceArray
     */
    public function buildObjectsFromRawData(array $data)
    {
        $type = $data['sys']['type'];

        switch ($type) {
            case 'Array':
                return $this->buildArray($data);
            case 'Locale':
                return $this->buildLocale($data);
            case 'Space':
                return $this->buildSpace($data);
            default:
                throw new \InvalidArgumentException('Unexpected type "' . $type . '"" while trying to build object.');
        }
    }

    /**
     * @param  object $object
     * @param  array  $data
     */
    public function updateObjectFromRawData($object, array $data)
    {
        $type = $data['sys']['type'];

        switch ($type) {
            case 'Space':
                $this->updateSpace($object, $data);
                break;
            case 'Locale':
                $this->updateLocale($object, $data);
                break;
            default:
                throw new \InvalidArgumentException('Unexpected type "' . $type . '"" while trying to update object.');
        }
    }

    private function buildLocale(array $data): Locale
    {
        return $this->createObject(Locale::class, [
            'name' => $data['name'],
            'code' => $data['code'],
            'contentDeliveryApi' => $data['contentDeliveryApi'],
            'contentManagementApi' => $data['contentManagementApi'],
            'default' => $data['default'],
            'optional' => $data['optional'],
            'sys' => $this->buildSystemProperties($data['sys'])
        ]);
    }

    private function updateLocale(Locale $locale, array $data)
    {
        return $this->updateObject(Locale::class, $locale, [
            'name' => $data['name'],
            'code' => $data['code'],
            'contentDeliveryApi' => $data['contentDeliveryApi'],
            'contentManagementApi' => $data['contentManagementApi'],
            'default' => $data['default'],
            'optional' => $data['optional'],
            'sys' => $this->buildSystemProperties($data['sys'])
        ]);
    }

    /**
     * @param  array $data
     *
     * @return Space
     */
    private function buildSpace(array $data): Space
    {
        return $this->createObject(Space::class, [
            'name' => $data['name'],
            'sys' => $this->buildSystemProperties($data['sys'])
        ]);
    }

    private function updateSpace(Space $space, array $data)
    {
        $this->updateObject(Space::class, $space, [
            'name' => $data['name'],
            'sys' => $this->buildSystemProperties($data['sys'])
        ]);
    }

    private function buildArray(array $data): ResourceArray
    {
        $items = [];
        foreach ($data['items'] as $item) {
            $items[] = $this->buildObjectsFromRawData($item);
        }

        return new ResourceArray($items, $data['total'], $data['limit'], $data['skip']);
    }

    /**
     * @param  string $class
     * @param  array  $properties
     *
     * @return object
     */
    private function createObject(string $class, array $properties)
    {
        $reflectedClass = new \ReflectionClass($class);
        $object = $reflectedClass->newInstanceWithoutConstructor();

        $hydrator = $this->getHydrator($class, $object);
        $hydrator($object, $properties);

        return $object;
    }

    /**
     * @param  string $class
     * @param  object $object
     * @param  array  $properties
     */
    private function updateObject(string $class, $object, array $properties)
    {
        $hydrator = $this->getHydrator($class, $object);
        $hydrator($object, $properties);
    }

    /**
     * @param  string $class
     * @param  object $object
     *
     * @return \Closure
     */
    private function getHydrator(string $class, $object): \Closure
    {
        if (isset(self::$hydratorCache[$class])) {
            return self::$hydratorCache[$class];
        }

        $hydrator = \Closure::bind(function ($object, $properties) {
            foreach ($properties as $property => $value) {
                $object->$property = $value;
            }
        }, null, $object);

        self::$hydratorCache[$class] = $hydrator;

        return $hydrator;
    }

    private function buildSystemProperties(array $sys): SystemProperties
    {
        return new SystemProperties($sys);
    }
}
