<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

class Space implements ResourceInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var SystemProperties
     */
    private $sys;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->sys = SystemProperties::withType('Space');
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param  string $name
     *
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return SystemProperties
     */
    public function getSystemProperties(): SystemProperties
    {
        return $this->sys;
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
        return (object) [
            'sys' => $this->sys,
            'name' => $this->name
        ];
    }
}
