<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Mapper\ContentType\Validation;

use Contentful\Management\Mapper\BaseMapper;
use Contentful\Management\Resource\ContentType\Validation\DateRangeValidation as ResourceClass;

/**
 * DateRangeValidation class.
 */
class DateRangeValidation extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        return $this->hydrate(ResourceClass::class, [
            'min' => $data['dateRange']['min'] ?? null,
            'max' => $data['dateRange']['max'] ?? null,
        ]);
    }
}
