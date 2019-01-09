<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties\Component;

trait VersionedTrait
{
    /**
     * @var int
     */
    protected $version;

    /**
     * @param array $data
     */
    protected function initVersioned(array $data)
    {
        $this->version = $data['version'] ?? $data['revision'];
    }

    /**
     * @return array
     */
    protected function jsonSerializeVersioned(): array
    {
        return [
            'version' => $this->version,
        ];
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }
}
