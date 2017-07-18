<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource;

use Contentful\Management\PublishedSystemProperties;

/**
 * PublishedContentType class.
 *
 * This class represents a resource with type "ContentType" (which was already published) in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/content-types/activated-content-type-collection
 */
class PublishedContentType implements \JsonSerializable
{
    /**
     * @var PublishedSystemProperties
     */
    private $sys;

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var \Contentful\Management\Field\FieldInterface[]
     */
    private $fields = [];

    /**
     * @var string|null
     */
    private $displayField;

    /**
     * PublishedContentType constructor.
     */
    public function __construct()
    {
        $this->sys = PublishedSystemProperties::withType('ContentType');
    }

    /**
     * @return PublishedSystemProperties
     */
    public function getSystemProperties(): PublishedSystemProperties
    {
        return $this->sys;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string|null
     */
    public function getDisplayField()
    {
        return $this->displayField;
    }

    /**
     * @return \Contentful\Management\Field\FieldInterface[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Returns an object to be used by `json_encode` to serialize objects of this class.
     *
     * @return object
     *
     * @see http://php.net/manual/en/jsonserializable.jsonserialize.php JsonSerializable::jsonSerialize
     *
     * @api
     */
    public function jsonSerialize()
    {
        $data = [
            'sys' => $this->sys,
            'name' => $this->name,
            'fields' => $this->fields
        ];

        if ($this->description !== null) {
            $data['description'] = $this->description;
        }

        if ($this->displayField !== null) {
            $data['displayField'] = $this->displayField;
        }

        return (object) $data;
    }
}
