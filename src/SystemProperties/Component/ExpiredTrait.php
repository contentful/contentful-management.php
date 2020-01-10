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

trait ExpiredTrait
{
    /**
     * @var DateTimeImmutable
     */
    protected $expiresAt;

    protected function initExpired(array $data)
    {
        $this->expiresAt = new DateTimeImmutable($data['expiresAt']);
    }

    protected function jsonSerializeExpired(): array
    {
        return [
            'expiresAt' => $this->expiresAt->jsonSerialize(),
        ];
    }

    public function getExpiresAt(): DateTimeImmutable
    {
        return $this->expiresAt;
    }
}
