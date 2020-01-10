<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Mapper\ContentType\Validation;

use Contentful\Management\Mapper\BaseMapper;
use Contentful\Management\Resource\ContentType\Validation\InValidation as ResourceClass;

/**
 * InValidation class.
 */
class InValidation extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        return new ResourceClass($data['in']);
    }
}
