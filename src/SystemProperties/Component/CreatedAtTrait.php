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

trait CreatedAtTrait
{
    /**
     * @var DateTimeImmutable
     */
    protected $createdAt;

    protected function initCreatedAt(array $data)
    {
        $this->createdAt = new DateTimeImmutable($data['createdAt']);
    }

    protected function jsonSerializeCreatedAt(): array
    {
        return [
            'createdAt' => $this->createdAt->jsonSerialize(),
        ];
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
