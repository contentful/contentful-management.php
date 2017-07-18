<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource;

use Contentful\DateHelper;
use Contentful\Management\Behavior\Archivable;
use Contentful\Management\Behavior\Creatable;
use Contentful\Management\Behavior\Deletable;
use Contentful\Management\Behavior\Publishable;
use Contentful\Management\Behavior\Updatable;
use Contentful\Management\SystemProperties;

/**
 * Entry class.
 *
 * This class represents a resource with type "Entry" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/entries
 */
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

    /**
     * Entry constructor.
     *
     * @param string $contentTypeId
     */
    public function __construct(string $contentTypeId)
    {
        $this->sys = new SystemProperties(['type' => 'Entry', 'contentType' => ['sys' => ['id' => $contentTypeId, 'linkType' => 'ContentType']]]);
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
    public function getResourceUriPart(): string
    {
        return 'entries';
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
     * @param string $name
     * @param mixed $value
     * @param string $locale
     */
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
