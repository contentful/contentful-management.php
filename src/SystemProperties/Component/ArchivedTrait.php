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
use Contentful\Core\Api\Link;

trait ArchivedTrait
{
    /**
     * @var DateTimeImmutable|null
     */
    protected $archivedAt;

    /**
     * @var Link|null
     */
    protected $archivedBy;

    /**
     * @var int|null
     */
    private $archivedVersion;

    /**
     * @param array $data
     */
    protected function initArchived(array $data)
    {
        if (!isset($data['archivedAt'])) {
            return;
        }

        $this->archivedAt = new DateTimeImmutable($data['archivedAt']);
        $this->archivedBy = new Link($data['archivedBy']['sys']['id'], $data['archivedBy']['sys']['linkType']);
        $this->archivedVersion = $data['archivedVersion'];
    }

    /**
     * @return array
     */
    protected function jsonSerializeArchived(): array
    {
        return \array_filter([
            'archivedAt' => $this->archivedAt,
            'archivedBy' => $this->archivedBy,
            'archivedVersion' => $this->archivedVersion,
        ]);
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getArchivedAt()
    {
        return $this->archivedAt;
    }

    /**
     * @return Link|null
     */
    public function getArchivedBy()
    {
        return $this->archivedBy;
    }

    /**
     * @return int|null
     */
    public function getArchivedVersion()
    {
        return $this->archivedVersion;
    }

    /**
     * @return bool
     */
    public function isArchived(): bool
    {
        return \null !== $this->archivedVersion;
    }
}
