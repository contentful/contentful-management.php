<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management;

use Contentful\Core\Api\DateTimeImmutable;
use Contentful\Core\Api\Link;
use Contentful\Core\Resource\SystemPropertiesInterface;

/**
 * SystemProperties class.
 *
 * This class represents a "sys" object in Contentful's responses.
 */
class SystemProperties implements SystemPropertiesInterface
{
    /**
     * @var string|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var Link|null
     */
    private $space;

    /**
     * @var Link|null
     */
    private $contentType;

    /**
     * @var DateTimeImmutable|null
     */
    private $createdAt;

    /**
     * @var DateTimeImmutable|null
     */
    private $updatedAt;

    /**
     * @var int|null
     */
    private $version;

    /**
     * This property is only present when dealing
     * with published content types.
     *
     * @var int|null
     */
    private $revision;

    /**
     * @var Link|null
     */
    private $createdBy;

    /**
     * @var Link|null
     */
    private $updatedBy;

    /**
     * @var DateTimeImmutable|null
     */
    private $firstPublishedAt;

    /**
     * @var int|null
     */
    private $publishedCounter;

    /**
     * @var DateTimeImmutable|null
     */
    private $publishedAt;

    /**
     * @var Link|null
     */
    private $publishedBy;

    /**
     * @var int|null
     */
    private $publishedVersion;

    /**
     * @var DateTimeImmutable|null
     */
    private $archivedAt;

    /**
     * @var Link|null
     */
    private $archivedBy;

    /**
     * @var int|null
     */
    private $archivedVersion;

    /**
     * @var string|null
     */
    private $snapshotType;

    /**
     * @var string|null
     */
    private $snapshotEntityType;

    /**
     * @var DateTimeImmutable|null
     */
    private $expiresAt;

    /**
     * SystemProperties constructor.
     *
     * @param array $sys Associative array of sys properties
     */
    public function __construct(array $sys = [])
    {
        $this->id = $sys['id'] ?? null;
        $this->type = $sys['type'] ?? null;
        $this->version = $sys['version'] ?? null;
        $this->revision = $sys['revision'] ?? null;
        $this->publishedCounter = $sys['publishedCounter'] ?? null;
        $this->publishedVersion = $sys['publishedVersion'] ?? null;
        $this->archivedVersion = $sys['archivedVersion'] ?? null;
        $this->snapshotType = $sys['snapshotType'] ?? null;
        $this->snapshotEntityType = $sys['snapshotEntityType'] ?? null;

        $this->createdAt = $this->checkAndBuildDate($sys, 'createdAt');
        $this->updatedAt = $this->checkAndBuildDate($sys, 'updatedAt');
        $this->publishedAt = $this->checkAndBuildDate($sys, 'publishedAt');
        $this->archivedAt = $this->checkAndBuildDate($sys, 'archivedAt');
        $this->firstPublishedAt = $this->checkAndBuildDate($sys, 'firstPublishedAt');
        $this->expiresAt = $this->checkAndBuildDate($sys, 'expiresAt');

        $this->space = $this->checkAndBuildLink($sys, 'space');
        $this->contentType = $this->checkAndBuildLink($sys, 'contentType');
        $this->createdBy = $this->checkAndBuildLink($sys, 'createdBy');
        $this->updatedBy = $this->checkAndBuildLink($sys, 'updatedBy');
        $this->publishedBy = $this->checkAndBuildLink($sys, 'publishedBy');
        $this->archivedBy = $this->checkAndBuildLink($sys, 'archivedBy');
    }

    /**
     * @param array  $data
     * @param string $field
     *
     * @return DateTimeImmutable|null
     */
    private function checkAndBuildDate(array $data, string $field)
    {
        return isset($data[$field])
            ? new DateTimeImmutable($data[$field])
            : null;
    }

    /**
     * @param array  $data
     * @param string $field
     *
     * @return Link|null
     */
    private function checkAndBuildLink(array $data, string $field)
    {
        return isset($data[$field])
            ? new Link($data[$field]['sys']['id'], $data[$field]['sys']['linkType'])
            : null;
    }

    /**
     * @return string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return Link|null
     */
    public function getSpace()
    {
        return $this->space;
    }

    /**
     * @return Link|null
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getArchivedAt()
    {
        return $this->archivedAt;
    }

    /**
     * @return int|null
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return int|null
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * @return Link|null
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @return Link|null
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getFirstPublishedAt()
    {
        return $this->firstPublishedAt;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * @return Link|null
     */
    public function getPublishedBy()
    {
        return $this->publishedBy;
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
    public function getPublishedCounter()
    {
        return $this->publishedCounter;
    }

    /**
     * @return int|null
     */
    public function getPublishedVersion()
    {
        return $this->publishedVersion;
    }

    /**
     * @return int|null
     */
    public function getArchivedVersion()
    {
        return $this->archivedVersion;
    }

    /**
     * @return string|null
     */
    public function getSnapshotType()
    {
        return $this->snapshotType;
    }

    /**
     * @return string|null
     */
    public function getSnapshotEntityType()
    {
        return $this->snapshotEntityType;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @return bool
     */
    public function isDraft(): bool
    {
        return null === $this->publishedVersion;
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return null !== $this->publishedVersion;
    }

    /**
     * @return bool
     */
    public function isUpdated(): bool
    {
        // The act of publishing an entity increases its version by 1, so any entry which has
        // 2 versions higher or more than the publishedVersion has unpublished changes.
        return null !== $this->publishedVersion && $this->version > $this->publishedVersion + 1;
    }

    /**
     * @return bool
     */
    public function isArchived(): bool
    {
        return null !== $this->archivedVersion;
    }

    /**
     * Returns an array to be used by "json_encode" to serialize objects of this class.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $sys = [];

        if (null !== $this->id) {
            $sys['id'] = $this->id;
        }
        if (null !== $this->type) {
            $sys['type'] = $this->type;
        }
        if (null !== $this->space) {
            $sys['space'] = $this->space;
        }
        if (null !== $this->contentType) {
            $sys['contentType'] = $this->contentType;
        }
        if (null !== $this->createdAt) {
            $sys['createdAt'] = (string) $this->createdAt;
        }
        if (null !== $this->updatedAt) {
            $sys['updatedAt'] = (string) $this->updatedAt;
        }
        if (null !== $this->archivedAt) {
            $sys['archivedAt'] = (string) $this->archivedAt;
        }
        if (null !== $this->publishedAt) {
            $sys['publishedAt'] = (string) $this->publishedAt;
        }
        if (null !== $this->firstPublishedAt) {
            $sys['firstPublishedAt'] = (string) $this->firstPublishedAt;
        }
        if (null !== $this->version) {
            $sys['version'] = $this->version;
        }
        if (null !== $this->revision) {
            $sys['revision'] = $this->revision;
        }
        if (null !== $this->createdBy) {
            $sys['createdBy'] = $this->createdBy;
        }
        if (null !== $this->updatedBy) {
            $sys['updatedBy'] = $this->updatedBy;
        }
        if (null !== $this->publishedBy) {
            $sys['publishedBy'] = $this->publishedBy;
        }
        if (null !== $this->archivedBy) {
            $sys['archivedBy'] = $this->archivedBy;
        }
        if (null !== $this->publishedCounter) {
            $sys['publishedCounter'] = $this->publishedCounter;
        }
        if (null !== $this->publishedVersion) {
            $sys['publishedVersion'] = $this->publishedVersion;
        }
        if (null !== $this->archivedVersion) {
            $sys['archivedVersion'] = $this->archivedVersion;
        }
        if (null !== $this->snapshotType) {
            $sys['snapshotType'] = $this->snapshotType;
        }
        if (null !== $this->snapshotEntityType) {
            $sys['snapshotEntityType'] = $this->snapshotEntityType;
        }
        if (null !== $this->expiresAt) {
            $sys['expiresAt'] = (string) $this->expiresAt;
        }

        return $sys;
    }
}
