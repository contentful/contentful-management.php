<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

use Contentful\File;
use Contentful\Management\Field\Validation;
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
     * @return Space|Asset|ContentType|Locale|ResourceArray
     */
    public function buildObjectsFromRawData(array $data)
    {
        $type = $data['sys']['type'];

        switch ($type) {
            case 'Array':
                return $this->buildArray($data);
            case 'Asset':
                return $this->buildAsset($data);
            case 'ContentType':
                return $this->buildContentType($data);
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
            case 'Asset':
                $this->updateAsset($object, $data);
                break;
            case 'ContentType':
                $this->updateContentType($object, $data);
                break;
            case 'Locale':
                $this->updateLocale($object, $data);
                break;
            default:
                throw new \InvalidArgumentException('Unexpected type "' . $type . '"" while trying to update object.');
        }
    }

    /**
     * @param  array $data
     *
     * @return Asset
     */
    private function buildAsset(array $data): Asset
    {
        $fields = $data['fields'];

        return $this->createObject(Asset::class, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'title' => isset($fields['title']) ? $fields['title'] : null,
            'description' => isset($fields['description']) ? $fields['description'] : null,
            'file' => isset($fields['file']) ? array_map([$this, 'buildFile'], $fields['file']) : null
        ]);
    }

    private function updateAsset(Asset $asset, array $data)
    {
        $fields = $data['fields'];

        return $this->updateObject(Asset::class, $asset, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'title' => isset($fields['title']) ? $fields['title'] : null,
            'description' => isset($fields['description']) ? $fields['description'] : null,
            'file' => isset($fields['file']) ? array_map([$this, 'buildFile'], $fields['file']) : null
        ]);
    }

    private function buildFile(array $data): File\FileInterface
    {
        if (isset($data['upload'])) {
            return new File\UploadFile($data['fileName'], $data['contentType'], $data['upload']);
        }

        $details = $data['details'];
        if (isset($details['image'])) {
            return new File\ImageFile(
                $data['fileName'],
                $data['contentType'],
                $data['url'],
                $details['size'],
                $details['image']['width'],
                $details['image']['height']
            );
        }

        return new File\File($data['fileName'], $data['contentType'], $data['url'], $details['size']);
    }

    private function buildContentType(array $data): ContentType
    {
        return $this->createObject(ContentType::class, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'name' => $data['name'],
            'description' => isset($data['description']) ? $data['description'] : null,
            'displayField' => isset($data['displayField']) ? $data['displayField'] : null,
            'fields' => array_map([$this, 'buildContentTypeField'], $data['fields'])
        ]);
    }

    private function updateContentType(ContentType $contentType, array $data)
    {
        $this->updateObject(ContentType::class, $contentType, [
            'sys' => $this->buildSystemProperties($data['sys']),
            'name' => $data['name'],
            'description' => isset($data['description']) ? $data['description'] : null,
            'displayField' => isset($data['displayField']) ? $data['displayField'] : null,
            'fields' => array_map([$this, 'buildContentTypeField'], $data['fields'])
        ]);
    }

    /**
     * @param  array $data
     *
     * @return Field\FieldInterface
     */
    private function buildContentTypeField(array $data): Field\FieldInterface
    {
        $fieldTypes = [
            'Array' => Field\ArrayField::class,
            'Boolean' => Field\BooleanField::class,
            'Date' => Field\DateField::class,
            'Integer' => Field\IntegerField::class,
            'Link' => Field\LinkField::class,
            'Location' => Field\LocationField::class,
            'Number' => Field\NumberField::class,
            'Object' => Field\ObjectField::class,
            'Symbol' => Field\SymbolField::class,
            'Text' => Field\TextField::class
        ];

        $type = $data['type'];

        $hydratorData = [
            'id' => $data['id'],
            'name' => $data['name'],
            'required' => $data['required'],
            'localized' => $data['localized'],
            'disabled' => isset($data['disabled']) ? $data['disabled'] : null,
            'omitted' => isset($data['omitted']) ? $data['omitted'] : null,
            'validations' => isset($data['validations']) ? array_map([$this, 'buildFieldValidation'], $data['validations']) : null
        ];

        if ($type === 'Link') {
            $hydratorData['linkType'] = $data['linkType'];
        }

        if ($type === 'Array') {
            $items = $data['items'];
            $hydratorData['itemsType'] = $items['type'];
            $hydratorData['itemsLinkType'] = isset($items['linkType']) ? $items['linkType'] : null;
            $hydratorData['itemsValidations'] = isset($items['validations']) ? array_map([$this, 'buildFieldValidation'], $items['validations']) : null;
        }

        return $this->createObject($fieldTypes[$type], $hydratorData);
    }

    /**
     * @param  array $data
     *
     * @return Validation\ValidationInterface
     */
    private function buildFieldValidation(array $data): Validation\ValidationInterface
    {
        $validations = [
            'size' => Validation\SizeValidation::class,
            'in' => Validation\InValidation::class,
            'linkContentType' => Validation\LinkContentTypeValidation::class,
            'linkMimetypeGroup' => Validation\LinkMimetypeGroupValidation::class,
            'range' => Validation\RangeValidation::class,
            'regexp' => Validation\RegexpValidation::class,
            'unique' => Validation\UniqueValidation::class,
            'dateRange' => Validation\DateRangeValidation::class,
            'assetImageDimensions' => Validation\AssetImageDimensionsValidation::class,
            'assetFileSize' => Validation\AssetFileSizeValidation::class
        ];

        $type = array_keys($data)[0];
        $class = $validations[$type];

        return $class::fromApiResponse($data);
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
