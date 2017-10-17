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
use Contentful\Management\Resource\ContentType\Validation\AssetFileSizeValidation as ResourceClass;

/**
 * AssetFileSizeValidation class.
 */
class AssetFileSizeValidation extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        return $this->hydrate(ResourceClass::class, [
            'min' => $data['assetFileSize']['min'] ?? null,
            'max' => $data['assetFileSize']['max'] ?? null,
        ]);
    }
}
