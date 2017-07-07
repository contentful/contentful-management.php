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
 * This class represents a `sys` object in Contentful's responses for published resources.
 */
class PublishedSystemProperties implements \JsonSerializable
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
    private $revision;

    /**
     * PublishedSystemProperties constructor.
     *
     * @param array $sys Associative array of sys properties
     */
    public function __construct(array $sys)
    {
        $this->id = $sys['id'] ?? null;
        $this->type = $sys['type'] ?? null;
        $this->space = isset($sys['space']) ? $this->buildLink($sys['space']) : null;
        $this->createdAt = isset($sys['createdAt']) ? new \DateTimeImmutable($sys['createdAt']) : null;
        $this->updatedAt = isset($sys['updatedAt']) ? new \DateTimeImmutable($sys['updatedAt']) : null;
        $this->revision = $sys['revision'] ?? null;
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
     * @return int|null
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * Returns an object to be used by `json_encode` to serialize objects of this class.
     *
     * @return object
     *
     * @see http://php.net/manual/en/jsonserializable.jsonserialize.php JsonSerializable::jsonSerialize
     */
    public function jsonSerialize()
    {
        $obj = new \stdClass;

        if ($this->id !== null) {
            $obj->id = $this->id;
        }
        if ($this->type !== null) {
            $obj->type = $this->type;
        }
        if ($this->space !== null) {
            $obj->space = $this->space;
        }
        if ($this->createdAt !== null) {
            $obj->createdAt = DateHelper::formatForJson($this->createdAt);
        }
        if ($this->updatedAt !== null) {
            $obj->updatedAt = DateHelper::formatForJson($this->updatedAt);
        }
        if ($this->revision !== null) {
            $obj->revision = $this->revision;
        }

        return $obj;
    }
}
