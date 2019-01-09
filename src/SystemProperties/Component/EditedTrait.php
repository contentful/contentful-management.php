<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties\Component;

trait EditedTrait
{
    use CreatedTrait,
        UpdatedTrait,
        VersionedTrait;

    /**
     * @param array $data
     */
    protected function initEdited(array $data)
    {
        $this->initCreated($data);
        $this->initUpdated($data);
        $this->initVersioned($data);
    }

    /**
     * @return array
     */
    protected function jsonSerializeEdited(): array
    {
        return \array_merge(
            $this->jsonSerializeCreated(),
            $this->jsonSerializeUpdated(),
            $this->jsonSerializeVersioned()
        );
    }
}
