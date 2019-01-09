<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
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

    /**
     * @param array $data
     */
    protected function initSnapshot(array $data)
    {
        $this->snapshotType = $data['snapshotType'];
        $this->snapshotEntityType = $data['snapshotEntityType'];
    }

    /**
     * @return array
     */
    protected function jsonSerializeSnapshot(): array
    {
        return \array_filter([
            'snapshotType' => $this->snapshotType,
            'snapshotEntityType' => $this->snapshotEntityType,
        ]);
    }

    /**
     * @return string
     */
    public function getSnapshotType(): string
    {
        return $this->snapshotType;
    }

    /**
     * @return string
     */
    public function getSnapshotEntityType(): string
    {
        return $this->snapshotEntityType;
    }
}
