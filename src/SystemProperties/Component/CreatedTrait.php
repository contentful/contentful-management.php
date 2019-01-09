<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties\Component;

trait CreatedTrait
{
    use CreatedAtTrait,
        CreatedByTrait;

    /**
     * @param array $data
     */
    protected function initCreated(array $data)
    {
        $this->initCreatedAt($data);
        $this->initCreatedBy($data);
    }

    /**
     * @return array
     */
    protected function jsonSerializeCreated(): array
    {
        return \array_merge(
            $this->jsonSerializeCreatedAt(),
            $this->jsonSerializeCreatedBy()
        );
    }
}
