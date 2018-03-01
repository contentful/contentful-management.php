<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Mapper;

use Contentful\Management\Resource\Locale as ResourceClass;
use Contentful\Management\SystemProperties;

/**
 * Locale class.
 *
 * This class is responsible for converting raw API data into a PHP object
 * of class Contentful\Management\Resource\Locale.
 */
class Locale extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        return $this->hydrate($resource ?: ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'name' => $data['name'],
            'code' => $data['code'],
            'contentDeliveryApi' => $data['contentDeliveryApi'],
            'contentManagementApi' => $data['contentManagementApi'],
            'default' => $data['default'],
            'optional' => $data['optional'],
        ]);
    }
}
