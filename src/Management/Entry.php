<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

class Entry implements \JsonSerializable
{
    /**
     * @var SystemProperties
     */
    private $sys;

    /**
     * @var array[]
     */
    private $fields = [];

    public function __construct()
    {
        $this->sys = SystemProperties::withType('Entry');
    }

    /**
     * @return SystemProperties
     */
    public function getSystemProperties(): SystemProperties
    {
        return $this->sys;
    }

    public function getField(string $name, string $locale)
    {
        if (!isset($this->fields[$name][$locale])) {
            return null;
        }

        return $this->fields[$name][$locale];
    }

    public function setField(string $name, $value, string $locale)
    {
        if (!isset($this->fields[$name])) {
            $this->fields[$name] = [];
        }

        $this->fields[$name][$locale] = $value;

        return $this;
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
        $fields = [];

        foreach ($this->fields as $fieldName => $fieldData) {
            $formattedData = [];
            foreach ($fieldData as $locale => $data) {
                if ($data instanceof \DateTimeInterface) {
                    $value = $this->formatDateForJson($data);
                } elseif (is_array($data)) {
                    $value = array_map(function ($value) {
                        if ($value instanceof \DateTimeInterface) {
                            return $this->formatDateForJson($value);
                        }

                        return $value;
                    }, $data);
                } else {
                    $value = $data;
                }
                $formattedData[$locale] = $value;
            }
            $fields[$fieldName] = (object) $formattedData;
        }

        return (object) [
            'fields' => (object) $fields,
            'sys' => $this->sys
        ];
    }

    /**
     * Unfortunately PHP has no easy way to create a nice, ISO 8601 formatted date string with milliseconds and Z
     * as the time zone specifier. Thus this hack.
     *
     * @param  \DateTimeInterface $dt
     *
     * @return string ISO 8601 formatted date
     */
    private function formatDateForJson(\DateTimeInterface $dt)
    {
        if ($dt instanceof \DateTime) {
            $dt = \DateTimeImmutable::createFromMutable($dt);
        }

        $dt = $dt->setTimezone(new \DateTimeZone('Etc/UTC'));

        return $dt->format('Y-m-d\TH:i:s.') . str_pad(floor($dt->format('u')/1000), 3, '0', STR_PAD_LEFT) . 'Z';
    }
}
