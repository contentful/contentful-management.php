<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties\Component;

trait UpdatedTrait
{
    use UpdatedAtTrait,
        UpdatedByTrait;

    /**
     * @param array $data
     */
    protected function initUpdated(array $data)
    {
        $this->initUpdatedAt($data);
        $this->initUpdatedBy($data);
    }

    /**
     * @return array
     */
    protected function jsonSerializeUpdated(): array
    {
        return \array_merge(
            $this->jsonSerializeUpdatedAt(),
            $this->jsonSerializeUpdatedBy()
        );
    }
}
