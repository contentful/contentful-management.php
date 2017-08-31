<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

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
class ContentType extends BaseResource implements Publishable, Deletable, Updatable, Creatable
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
     * {@inheritdoc}
     */
    public function getResourceUriPart(): string
    {
        return 'content_types';
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
}
