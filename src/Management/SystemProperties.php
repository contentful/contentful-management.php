<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

use Contentful\DateHelper;
use Contentful\Link;

/**
 * SystemProperties class.
 *
 * This class represents a `sys` object in Contentful's responses.
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
     * @var \DateTimeImmutable|null
     */
    private $createdAt;

    /**
     * @var \DateTimeImmutable|null
     */
    private $updatedAt;

    /**
     * @var int|null
     */
    private $version;

    /**
     * @var Link|null
     */
    private $createdBy;

    /**
     * @var Link|null
     */
    private $updatedBy;

    /**
     * @var \DateTimeImmutable|null
     */
    private $firstPublishedAt;

    /**
     * @var int|null
     */
    private $publishedCounter;

    /**
     * @var \DateTimeImmutable|null
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
     * @var \DateTimeImmutable|null
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
     * @var \DateTimeImmutable|null
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
        $this->space = isset($sys['space']) ? $this->buildLink($sys['space']) : null;
        $this->contentType = isset($sys['contentType']) ? $this->buildLink($sys['contentType']) : null;
        $this->createdAt = isset($sys['createdAt']) ? new \DateTimeImmutable($sys['createdAt']) : null;
        $this->updatedAt = isset($sys['updatedAt']) ? new \DateTimeImmutable($sys['updatedAt']) : null;
        $this->publishedAt = isset($sys['publishedAt']) ? new \DateTimeImmutable($sys['publishedAt']) : null;
        $this->archivedAt = isset($sys['archivedAt']) ? new \DateTimeImmutable($sys['archivedAt']) : null;
        $this->firstPublishedAt = isset($sys['firstPublishedAt']) ? new \DateTimeImmutable($sys['firstPublishedAt']) : null;
        $this->version = $sys['version'] ?? null;
        $this->publishedCounter = $sys['publishedCounter'] ?? null;
        $this->publishedVersion = $sys['publishedVersion'] ?? null;
        $this->archivedVersion = $sys['archivedVersion'] ?? null;
        $this->createdBy = isset($sys['createdBy']) ? $this->buildLink($sys['createdBy']) : null;
        $this->updatedBy = isset($sys['updatedBy']) ? $this->buildLink($sys['updatedBy']) : null;
        $this->publishedBy = isset($sys['publishedBy']) ? $this->buildLink($sys['publishedBy']) : null;
        $this->archivedBy = isset($sys['archivedBy']) ? $this->buildLink($sys['archivedBy']) : null;
        $this->snapshotType = $sys['snapshotType'] ?? null;
        $this->snapshotEntityType = $sys['snapshotEntityType'] ?? null;
        $this->expiresAt = isset($sys['expiresAt']) ? new \DateTimeImmutable($sys['expiresAt']) : null;
    }

    /**
     * Creates an instance using the given `type` value.
     *
     * @param string $type
     *
     * @return SystemProperties
     */
    public static function withType(string $type)
    {
        return new self(['type' => $type]);
    }

    /**
     * @param array $data
     *
     * @return Link
     */
    private function buildLink(array $data)
    {
        $sys = $data['sys'];

        return new Link($sys['id'], $sys['linkType']);
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
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return \DateTimeImmutable|null
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
     * @return \DateTimeImmutable|null
     */
    public function getFirstPublishedAt()
    {
        return $this->firstPublishedAt;
    }

    /**
     * @return \DateTimeImmutable|null
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
     * @return \DateTimeImmutable|null
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Returns an array to be used by `json_encode` to serialize objects of this class.
     *
     * @return array
     *
     * @see http://php.net/manual/en/jsonserializable.jsonserialize.php JsonSerializable::jsonSerialize
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
            $sys['createdAt'] = DateHelper::formatForJson($this->createdAt);
        }
        if ($this->updatedAt !== null) {
            $sys['updatedAt'] = DateHelper::formatForJson($this->updatedAt);
        }
        if ($this->archivedAt !== null) {
            $sys['archivedAt'] = DateHelper::formatForJson($this->archivedAt);
        }
        if ($this->publishedAt !== null) {
            $sys['publishedAt'] = DateHelper::formatForJson($this->publishedAt);
        }
        if ($this->firstPublishedAt !== null) {
            $sys['firstPublishedAt'] = DateHelper::formatForJson($this->firstPublishedAt);
        }
        if ($this->version !== null) {
            $sys['version'] = $this->version;
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
            $sys['expiresAt'] = $this->expiresAt;
        }

        return $sys;
    }
}
