<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties\Component;

trait CreatedTrait
{
    use CreatedAtTrait;
    use
        CreatedByTrait;

    protected function initCreated(array $data)
    {
        $this->initCreatedAt($data);
        $this->initCreatedBy($data);
    }

    protected function jsonSerializeCreated(): array
    {
        return \array_merge(
            $this->jsonSerializeCreatedAt(),
            $this->jsonSerializeCreatedBy()
        );
    }
}
