<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties\Component;

trait SnapshotTrait
{
    /**
     * @var string
     */
    private $snapshotType;

    /**
     * @var string
     */
    private $snapshotEntityType;

    protected function initSnapshot(array $data)
    {
        $this->snapshotType = $data['snapshotType'];
        $this->snapshotEntityType = $data['snapshotEntityType'];
    }

    protected function jsonSerializeSnapshot(): array
    {
        return \array_filter([
            'snapshotType' => $this->snapshotType,
            'snapshotEntityType' => $this->snapshotEntityType,
        ]);
    }

    public function getSnapshotType(): string
    {
        return $this->snapshotType;
    }

    public function getSnapshotEntityType(): string
    {
        return $this->snapshotEntityType;
    }
}
