<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Management\Resource\Behavior\Creatable;
use Contentful\Management\Resource\Behavior\Deletable;
use Contentful\Management\Resource\Behavior\Publishable;
use Contentful\Management\Resource\Behavior\Updatable;
use Contentful\Management\Resource\ContentType\Field\FieldInterface;

/**
 * ContentType class.
 *
 * This class represents a resource with type "ContentType" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/content-types
 * @see https://www.contentful.com/developers/docs/concepts/data-model/
 */
class ContentType extends BaseResource implements Creatable, Updatable, Deletable, Publishable
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string|null
     */
    protected $displayField;

    /**
     * @var FieldInterface[]
     */
    protected $fields = [];

    /**
     * @var bool
     */
    protected $isPublished = false;

    /**
     * ContentType constructor.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct('ContentType');
        $this->name = $name;
    }

    /**
     * Returns an array to be used by "json_encode" to serialize objects of this class.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $data = [
            'sys' => $this->sys,
            'name' => $this->name,
            'fields' => $this->fields,
        ];

        if ($this->description !== null) {
            $data['description'] = $this->description;
        }

        if ($this->displayField !== null) {
            $data['displayField'] = $this->displayField;
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return static
     */
    public function setName(string $name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return static
     */
    public function setDescription(string $description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDisplayField()
    {
        return $this->displayField;
    }

    /**
     * @param string|null $displayField
     *
     * @return static
     */
    public function setDisplayField(string $displayField = null)
    {
        $this->displayField = $displayField;

        return $this;
    }

    /**
     * @return FieldInterface[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param string $fieldId
     *
     * @throws \InvalidArgumentException
     *
     * @return FieldInterface
     */
    public function getField(string $fieldId): FieldInterface
    {
        foreach ($this->fields as $field) {
            if ($field->getId() == $fieldId) {
                return $field;
            }
        }

        throw new \InvalidArgumentException(sprintf(
            'Trying to access invalid field "%s" on content type "%s".',
            $fieldId,
            $this->getId()
        ));
    }

    /**
     * @param FieldInterface[] $fields
     *
     * @return static
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @param FieldInterface $contentTypeField
     *
     * @return static
     */
    public function addField(FieldInterface $contentTypeField)
    {
        $this->fields[] = $contentTypeField;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->isPublished;
    }

    /**
     * Adds a new content type field, and returns it.
     *
     * @param string $type    A valid field type, must be a class in the Contentful\Management\Resource\ContentType\Field namespace
     * @param string $fieldId The field ID
     * @param string $name    The field name
     * @param array  $params  Extra parameters that will be forwarded to the field object constructor
     *
     * @return FieldInterface
     */
    public function addNewField(string $type, string $fieldId, string $name, ...$params): FieldInterface
    {
        $field = $this->createField($type, $fieldId, $name, ...$params);
        $this->addField($field);

        return $field;
    }

    /**
     * Shortcut for creating a new field object.
     *
     * @param string $type    A valid field type, must be a class in the Contentful\Management\Resource\ContentType\Field namespace
     * @param string $fieldId The field ID
     * @param string $name    The field name
     * @param array  $params  Extra parameters that will be forwarded to the field object constructor
     *
     * @return FieldInterface
     */
    public function createField(string $type, string $fieldId, string $name, ...$params): FieldInterface
    {
        $class = '\\Contentful\\Management\\Resource\\ContentType\\Field\\'.\ucfirst($type).'Field';

        if (!\class_exists($class)) {
            throw new \InvalidArgumentException(sprintf(
                'Trying to instantiate invalid field class "%s".',
                $type
            ));
        }

        return new $class($fieldId, $name, ...$params);
    }
}
