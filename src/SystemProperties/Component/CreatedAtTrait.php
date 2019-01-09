<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
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

    /**
     * @param array $data
     */
    protected function initCreatedAt(array $data)
    {
        $this->createdAt = new DateTimeImmutable($data['createdAt']);
    }

    /**
     * @return array
     */
    protected function jsonSerializeCreatedAt(): array
    {
        return [
            'createdAt' => $this->createdAt->jsonSerialize(),
        ];
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
