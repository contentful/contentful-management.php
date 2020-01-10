<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties\Component;

use Contentful\Core\Api\DateTimeImmutable;

trait UpdatedAtTrait
{
    /**
     * @var DateTimeImmutable
     */
    protected $updatedAt;

    protected function initUpdatedAt(array $data)
    {
        $this->updatedAt = new DateTimeImmutable($data['updatedAt']);
    }

    protected function jsonSerializeUpdatedAt(): array
    {
        return [
            'updatedAt' => $this->updatedAt->jsonSerialize(),
        ];
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
