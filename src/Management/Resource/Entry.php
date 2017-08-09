<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource;

use function Contentful\format_date_for_json;
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
    protected $sys;

    /**
     * @var array[]
     */
    protected $fields = [];

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
     * {@inheritdoc}
     */
    public function getSystemProperties(): SystemProperties
    {
        return $this->sys;
    }

    /**
     * {@inheritdoc}
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
     * @param string|null $locale
     *
     * @return array
     */
    public function getFields(string $locale = null): array
    {
        if ($locale === null) {
            return $this->fields;
        }

        $fields = [];
        foreach ($this->fields as $name => $field) {
            $fields[$name] = $field[$locale] ?? null;
        }

        return $fields;
    }

    /**
     * @param string $name
     * @param string $locale
     * @param mixed  $value
     */
    public function setField(string $name, string $locale, $value)
    {
        if (!isset($this->fields[$name])) {
            $this->fields[$name] = [];
        }

        $this->fields[$name][$locale] = $value;

        return $this;
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
        $fields = [];

        foreach ($this->fields as $fieldName => $fieldData) {
            $fields[$fieldName] = [];

            foreach ($fieldData as $locale => $data) {
                $fields[$fieldName][$locale] = $this->getFormattedData($data);
            }
        }

        return [
            'sys' => $this->sys,
            'fields' => (object) $fields,
        ];
    }

    /**
     * Formats data for JSON encoding.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    private function getFormattedData($data)
    {
        if ($data instanceof \DateTimeImmutable) {
            return format_date_for_json($data);
        }

        if ($data instanceof \DateTime) {
            return format_date_for_json(\DateTimeImmutable::createFromMutable($data));
        }

        if (is_array($data)) {
            return array_map([$this, 'getFormattedData'], $data);
        }

        return $data;
    }
}
