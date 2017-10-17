<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Mapper;

use Contentful\Management\Resource\Upload as ResourceClass;
use Contentful\Management\SystemProperties;

/**
 * Upload class.
 *
 * This class is responsible for converting raw API data into a PHP object
 * of class Contentful\Management\Resource\Upload.
 */
class Upload extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        return $this->hydrate($resource ?: ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'body' => null,
        ]);
    }
}
