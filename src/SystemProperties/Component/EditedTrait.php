<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties\Component;

trait EditedTrait
{
    use CreatedTrait;
    use
        UpdatedTrait;
    use
        VersionedTrait;

    protected function initEdited(array $data)
    {
        $this->initCreated($data);
        $this->initUpdated($data);
        $this->initVersioned($data);
    }

    protected function jsonSerializeEdited(): array
    {
        return \array_merge(
            $this->jsonSerializeCreated(),
            $this->jsonSerializeUpdated(),
            $this->jsonSerializeVersioned()
        );
    }
}
