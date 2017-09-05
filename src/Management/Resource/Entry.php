<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource;

use Contentful\Management\Resource\Behavior\Archivable;
use Contentful\Management\Resource\Behavior\Creatable;
use Contentful\Management\Resource\Behavior\Deletable;
use Contentful\Management\Resource\Behavior\Publishable;
use Contentful\Management\Resource\Behavior\Updatable;
use function Contentful\format_date_for_json;

/**
 * Entry class.
 *
 * This class represents a resource with type "Entry" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/entries
 */
class Entry extends BaseResource implements Creatable, Updatable, Deletable, Publishable, Archivable
{
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
        parent::__construct('Entry', ['contentType' => ['sys' => ['id' => $contentTypeId, 'linkType' => 'ContentType']]]);
    }

    /**
     * Returns an array to be used by "json_encode" to serialize objects of this class.
     *
     * @return array
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

        if (is_array($data)) {
            return array_map([$this, 'getFormattedData'], $data);
        }

        return $data;
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
     *
     * @return static
     */
    public function setField(string $name, string $locale, $value)
    {
        if (!isset($this->fields[$name])) {
            $this->fields[$name] = [];
        }

        $this->fields[$name][$locale] = $value;

        return $this;
    }
}
