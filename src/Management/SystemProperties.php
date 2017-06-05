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
     * SystemProperties constructor.
     *
     * @param  array $sys Associative array of sys properties
     */
    public function __construct(array $sys = [])
    {
        $this->id = isset($sys['id']) ? $sys['id'] : null;
        $this->type = isset($sys['type']) ? $sys['type'] : null;
        $this->createdAt = isset($sys['createdAt']) ? new \DateTimeImmutable($sys['createdAt']) : null;
        $this->updatedAt = isset($sys['updatedAt']) ? new \DateTimeImmutable($sys['updatedAt']) : null;
        $this->version = isset($sys['version']) ? $sys['version'] : null;
        $this->createdBy = isset($sys['createdBy']) ? $this->buildLink($sys['createdBy']) : null;
        $this->updatedBy = isset($sys['updatedBy']) ? $this->buildLink($sys['updatedBy']) : null;
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
        if ($this->createdAt !== null) {
            $obj->createdAt = $this->formatDateForJson($this->createdAt);
        }
        if ($this->updatedAt !== null) {
            $obj->updatedAt = $this->formatDateForJson($this->updatedAt);
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
