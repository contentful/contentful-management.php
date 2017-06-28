<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

use Contentful\DateHelper;

class Entry implements SpaceScopedResourceInterface, Publishable, Archivable, Deletable, Updatable, Creatable
{
    /**
     * @var SystemProperties
     */
    private $sys;

    /**
     * @var array[]
     */
    private $fields = [];

    public function __construct(string $contentTypeId)
    {
        $this->sys = new SystemProperties(['type' => 'Entry', 'contentType' => ['sys' => ['id' => $contentTypeId, 'linkType' => 'ContentType']]]);
    }

    /**
     * @return SystemProperties
     */
    public function getSystemProperties(): SystemProperties
    {
        return $this->sys;
    }

    public function getResourceUrlPart(): string
    {
        return 'entries';
    }

    public function getField(string $name, string $locale)
    {
        return $this->fields[$name][$locale] ?? null;
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
                if ($data instanceof \DateTimeImmutable) {
                    $value = DateHelper::formatForJson($data);
                } elseif ($data instanceof \DateTime) {
                    $dt = \DateTimeImmutable::createFromMutable($data);
                    $value = DateHelper::formatForJson($dt);
                } elseif (is_array($data)) {
                    $value = array_map(function ($value) {
                        if ($value instanceof \DateTimeImmutable) {
                            return DateHelper::formatForJson($value);
                        }
                        if ($value instanceof \DateTime) {
                            $dt = \DateTimeImmutable::createFromMutable($value);

                            return DateHelper::formatForJson($dt);
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
}
