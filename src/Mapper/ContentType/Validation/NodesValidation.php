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
use Contentful\Management\Resource\ContentType\Validation\NodesValidation as ResourceClass;

/**
 * NoedsValidation class stub.
 */
class NodesValidation extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        return new ResourceClass(
            isset($data['nodes']['asset-hyperlink']) ?
                \array_map([$this, 'mapValidation'], $data['nodes']['asset-hyperlink']) :
                [],
            isset($data['nodes']['embedded-asset-block']) ?
                \array_map([$this, 'mapValidation'], $data['nodes']['embedded-asset-block']) :
                [],
            isset($data['nodes']['embedded-entry-block']) ?
                \array_map([$this, 'mapValidation'], $data['nodes']['embedded-entry-block']) :
                [],
            isset($data['nodes']['embedded-entry-inline']) ?
                \array_map([$this, 'mapValidation'], $data['nodes']['embedded-entry-inline']) :
                [],
            isset($data['nodes']['entry-hyperlink']) ?
                \array_map([$this, 'mapValidation'], $data['nodes']['entry-hyperlink']) :
                []
        );
    }
}
