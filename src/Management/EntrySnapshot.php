<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

use Contentful\DateHelper;

/**
 * EntrySnapshot class.
 *
 * This class represents a resource with type "Snapshot" and entity type "Entry" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/entry-snapshots-collection
 * @see https://www.contentful.com/faq/versioning/
 */
class EntrySnapshot implements SpaceScopedResourceInterface
{
    /**
     * @var SystemProperties
     */
    private $sys;

    /**
     * @var array[]
     */
    private $fields = [];

    /**
     * @var SystemProperties
     */
    private $entrySys;

    /**
     * EntrySnapshot constructor.
     */
    public function __construct()
    {
        $this->sys = new SystemProperties(['type' => 'Snapshot', 'snapshotEntityType' => 'Entry']);
        $this->entrySys = new SystemProperties(['type' => 'Entry']);
    }

    /**
     * {@inheritDoc}
     */
    public function getSystemProperties(): SystemProperties
    {
        return $this->sys;
    }

    /**
     * {@inheritDoc}
     */
    public function getResourceUrlPart(): string
    {
        return 'entries';
    }

    /**
     * @return array[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param string $name
     * @param string $locale
     *
     * @return mixed
     */
    public function getField(string $name, string $locale)
    {
        return $this->fields[$name][$locale] ?? null;
    }

    /**
     * @return SystemProperties
     */
    public function getEntrySystemProperties(): SystemProperties
    {
        return $this->entrySys;
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
            'snapshot' => [
                'fields' => (object) $fields,
                'sys' => $this->entrySys,
            ],
            'sys' => $this->sys,
        ];
    }
}
