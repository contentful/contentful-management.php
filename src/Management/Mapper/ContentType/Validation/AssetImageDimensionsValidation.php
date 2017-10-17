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
use Contentful\Management\Resource\ContentType\Validation\AssetImageDimensionsValidation as ResourceClass;

/**
 * AssetImageDimensionsValidation class.
 */
class AssetImageDimensionsValidation extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        return $this->hydrate(ResourceClass::class, [
            'minWidth' => $data['assetImageDimensions']['width']['min'] ?? null,
            'maxWidth' => $data['assetImageDimensions']['width']['max'] ?? null,
            'minHeight' => $data['assetImageDimensions']['height']['min'] ?? null,
            'maxHeight' => $data['assetImageDimensions']['height']['max'] ?? null,
        ]);
    }
}
