<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management;

use Contentful\Link;

/**
 * SystemProperties class.
 *
 * This class represents a "sys" object in Contentful's responses.
 */
class SystemProperties implements \JsonSerializable
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
     * @var ApiDateTime|null
     */
    private $createdAt;

    /**
     * @var ApiDateTime|null
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
     * @var ApiDateTime|null
     */
    private $firstPublishedAt;

    /**
     * @var int|null
     */
    private $publishedCounter;

    /**
     * @var ApiDateTime|null
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
     * @var ApiDateTime|null
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
     * @var ApiDateTime|null
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
     * @return ApiDateTime|null
     */
    private function checkAndBuildDate(array $data, string $field)
    {
        return isset($data[$field])
            ? new ApiDateTime($data[$field])
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
     * @return ApiDateTime|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return ApiDateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return ApiDateTime|null
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
     * @return ApiDateTime|null
     */
    public function getFirstPublishedAt()
    {
        return $this->firstPublishedAt;
    }

    /**
     * @return ApiDateTime|null
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
     * @return ApiDateTime|null
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
        return $this->publishedVersion === null;
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->publishedVersion !== null;
    }

    /**
     * @return bool
     */
    public function isUpdated(): bool
    {
        // The act of publishing an entity increases its version by 1, so any entry which has
        // 2 versions higher or more than the publishedVersion has unpublished changes.
        return $this->publishedVersion !== null && $this->version > $this->publishedVersion + 1;
    }

    /**
     * @return bool
     */
    public function isArchived(): bool
    {
        return $this->archivedVersion !== null;
    }

    /**
     * Returns an array to be used by "json_encode" to serialize objects of this class.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $sys = [];

        if ($this->id !== null) {
            $sys['id'] = $this->id;
        }
        if ($this->type !== null) {
            $sys['type'] = $this->type;
        }
        if ($this->space !== null) {
            $sys['space'] = $this->space;
        }
        if ($this->contentType !== null) {
            $sys['contentType'] = $this->contentType;
        }
        if ($this->createdAt !== null) {
            $sys['createdAt'] = (string) $this->createdAt;
        }
        if ($this->updatedAt !== null) {
            $sys['updatedAt'] = (string) $this->updatedAt;
        }
        if ($this->archivedAt !== null) {
            $sys['archivedAt'] = (string) $this->archivedAt;
        }
        if ($this->publishedAt !== null) {
            $sys['publishedAt'] = (string) $this->publishedAt;
        }
        if ($this->firstPublishedAt !== null) {
            $sys['firstPublishedAt'] = (string) $this->firstPublishedAt;
        }
        if ($this->version !== null) {
            $sys['version'] = $this->version;
        }
        if ($this->revision !== null) {
            $sys['revision'] = $this->revision;
        }
        if ($this->createdBy !== null) {
            $sys['createdBy'] = $this->createdBy;
        }
        if ($this->updatedBy !== null) {
            $sys['updatedBy'] = $this->updatedBy;
        }
        if ($this->publishedBy !== null) {
            $sys['publishedBy'] = $this->publishedBy;
        }
        if ($this->archivedBy !== null) {
            $sys['archivedBy'] = $this->archivedBy;
        }
        if ($this->publishedCounter !== null) {
            $sys['publishedCounter'] = $this->publishedCounter;
        }
        if ($this->publishedVersion !== null) {
            $sys['publishedVersion'] = $this->publishedVersion;
        }
        if ($this->archivedVersion !== null) {
            $sys['archivedVersion'] = $this->archivedVersion;
        }
        if ($this->snapshotType !== null) {
            $sys['snapshotType'] = $this->snapshotType;
        }
        if ($this->snapshotEntityType !== null) {
            $sys['snapshotEntityType'] = $this->snapshotEntityType;
        }
        if ($this->expiresAt !== null) {
            $sys['expiresAt'] = (string) $this->expiresAt;
        }

        return $sys;
    }
}
