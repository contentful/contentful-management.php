<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
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
        return new ResourceClass(
            $data['assetImageDimensions']['width']['min'] ?? \null,
            $data['assetImageDimensions']['width']['max'] ?? \null,
            $data['assetImageDimensions']['height']['min'] ?? \null,
            $data['assetImageDimensions']['height']['max'] ?? \null
        );
    }
}
