<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Mapper;

use Contentful\Management\Resource\ContentType as ResourceClass;
use Contentful\Management\Resource\ContentType\Field\FieldInterface;
use Contentful\Management\SystemProperties\ContentType as SystemProperties;

/**
 * ContentType class.
 *
 * This class is responsible for converting raw API data into a PHP object
 * of class Contentful\Management\Resource\ContentType.
 */
class ContentType extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        /** @var ResourceClass $contentType */
        $contentType = $this->hydrator->hydrate($resource ?: ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'name' => $data['name'],
            'description' => $data['description'] ?? \null,
            'displayField' => $data['displayField'] ?? \null,
            'fields' => \array_map([$this, 'mapField'], $data['fields']),
            'isPublished' => isset($data['sys']['revision']),
        ]);

        return $contentType;
    }

    /**
     * @param array $data
     *
     * @return FieldInterface
     */
    protected function mapField(array $data): FieldInterface
    {
        $fqcn = __NAMESPACE__.'\\ContentType\\Field\\'.$data['type'].'Field';

        /** @var FieldInterface $field */
        $field = $this->builder->getMapper($fqcn)
            ->map(\null, $data)
        ;

        return $field;
    }
}
