<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

use Contentful\Link;

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
     * SystemProperties constructor.
     *
     * @param  array $sys Associative array of sys properties
     */
    public function __construct(array $sys = [])
    {
        $this->id = isset($sys['id']) ? $sys['id'] : null;
        $this->type = isset($sys['type']) ? $sys['type'] : null;
        $this->space = isset($sys['space']) ? $this->buildLink($sys['space']) : null;
        $this->createdAt = isset($sys['createdAt']) ? new \DateTimeImmutable($sys['createdAt']) : null;
        $this->updatedAt = isset($sys['updatedAt']) ? new \DateTimeImmutable($sys['updatedAt']) : null;
        $this->publishedAt = isset($sys['publishedAt']) ? new \DateTimeImmutable($sys['publishedAt']) : null;
        $this->archivedAt = isset($sys['archivedAt']) ? new \DateTimeImmutable($sys['archivedAt']) : null;
        $this->firstPublishedAt = isset($sys['firstPublishedAt']) ? new \DateTimeImmutable($sys['firstPublishedAt']) : null;
        $this->version = isset($sys['version']) ? $sys['version'] : null;
        $this->publishedCounter = isset($sys['publishedCounter']) ? $sys['publishedCounter'] : null;
        $this->publishedVersion = isset($sys['publishedVersion']) ? $sys['publishedVersion'] : null;
        $this->archivedVersion = isset($sys['archivedVersion']) ? $sys['archivedVersion'] : null;
        $this->createdBy = isset($sys['createdBy']) ? $this->buildLink($sys['createdBy']) : null;
        $this->updatedBy = isset($sys['updatedBy']) ? $this->buildLink($sys['updatedBy']) : null;
        $this->publishedBy = isset($sys['publishedBy']) ? $this->buildLink($sys['publishedBy']) : null;
        $this->archivedBy = isset($sys['archivedBy']) ? $this->buildLink($sys['archivedBy']) : null;
    }

    public static function withType(string $type)
    {
        return new self(['type' => $type]);
    }

    /**
     * @param  array $data
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
            $obj->createdAt = $this->formatDateForJson($this->createdAt);
        }
        if ($this->updatedAt !== null) {
            $obj->updatedAt = $this->formatDateForJson($this->updatedAt);
        }
        if ($this->archivedAt !== null) {
            $obj->archivedAt = $this->formatDateForJson($this->archivedAt);
        }
        if ($this->publishedAt !== null) {
            $obj->publishedAt = $this->formatDateForJson($this->publishedAt);
        }
        if ($this->firstPublishedAt !== null) {
            $obj->firstPublishedAt = $this->formatDateForJson($this->firstPublishedAt);
        }
        if ($this->version !== null) {
            $obj->version = $this->version;
        }
        if ($this->createdBy !== null) {
            $obj->createdBy = $this->createdBy;
        }
        if ($this->updatedBy !== null) {
            $obj->updatedBy = $this->updatedBy;
        }
        if ($this->publishedBy !== null) {
            $obj->publishedBy = $this->publishedBy;
        }
        if ($this->archivedBy !== null) {
            $obj->archivedBy = $this->archivedBy;
        }
        if ($this->publishedCounter !== null) {
            $obj->publishedCounter = $this->publishedCounter;
        }
        if ($this->publishedVersion !== null) {
            $obj->publishedVersion = $this->publishedVersion;
        }
        if ($this->archivedVersion !== null) {
            $obj->archivedVersion = $this->archivedVersion;
        }

        return $obj;
    }

    /**
     * Unfortunately PHP has no easy way to create a nice, ISO 8601 formatted date string with milliseconds and Z
     * as the time zone specifier. Thus this hack.
     *
     * @param  \DateTimeImmutable $dt
     *
     * @return string ISO 8601 formatted date
     */
    private function formatDateForJson(\DateTimeImmutable $dt): string
    {
        $dt = $dt->setTimezone(new \DateTimeZone('Etc/UTC'));
        $result = $dt->format('Y-m-d\TH:i:s') ;
        $milliseconds =floor($dt->format('u')/1000);
        if ($milliseconds > 0) {
            $result .= '.' . str_pad($milliseconds, 3, '0', STR_PAD_LEFT);
        }

        return $result . 'Z';
    }
}
