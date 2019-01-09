<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Management\Resource\Behavior\CreatableInterface;
use Contentful\Management\Resource\Behavior\DeletableTrait;
use Contentful\Management\Resource\Behavior\UpdatableTrait;
use Contentful\Management\Resource\Extension\FieldType;
use Contentful\Management\Resource\Extension\Parameter;
use Contentful\Management\SystemProperties\Extension as SystemProperties;

/**
 * Extension class.
 *
 * This class represents a resource with type "Extension" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/ui-extensions
 * @see https://www.contentful.com/r/knowledgebase/ui-extensions-guide/
 */
class Extension extends BaseResource implements CreatableInterface
{
    use DeletableTrait,
        UpdatableTrait;

    /**
     * @var SystemProperties
     */
    protected $sys;

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $source = '';

    /**
     * @var FieldType[]
     */
    protected $fieldTypes = [];

    /**
     * @var bool
     */
    protected $sidebar;

    /**
     * @var Parameter[]
     */
    protected $installationParameters = [];

    /**
     * @var Parameter[]
     */
    protected $instanceParameters = [];

    /**
     * Extension construct.
     *
     * @param string $name
     */
    public function __construct(string $name = '')
    {
        $this->name = $name;
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
    public function asUriParameters(): array
    {
        return [
            'space' => $this->sys->getSpace()->getId(),
            'environment' => $this->sys->getEnvironment()->getId(),
            'extension' => $this->sys->getId(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getHeadersForCreation(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return static
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @param string $source Either the full extension code, or an URL
     *
     * @return static
     */
    public function setSource(string $source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return FieldType[]
     */
    public function getFieldTypes(): array
    {
        return $this->fieldTypes;
    }

    /**
     * @param FieldType[] $fieldTypes
     *
     * @return static
     */
    public function setFieldTypes(array $fieldTypes)
    {
        $this->fieldTypes = [];

        \array_map([$this, 'addFieldType'], $fieldTypes);

        return $this;
    }

    /**
     * @param FieldType $fieldType
     *
     * @return static
     */
    public function addFieldType(FieldType $fieldType)
    {
        $this->fieldTypes[] = $fieldType;

        return $this;
    }

    /**
     * Shortcut for adding a new field type.
     *
     * @param string $type
     * @param array  $options
     *
     * @return static
     */
    public function addNewFieldType(string $type, array $options = [])
    {
        return $this->addFieldType(new FieldType($type, $options));
    }

    /**
     * @return bool
     */
    public function isSidebar(): bool
    {
        return $this->sidebar;
    }

    /**
     * @param bool $sidebar
     *
     * @return static
     */
    public function setSidebar(bool $sidebar)
    {
        $this->sidebar = $sidebar;

        return $this;
    }

    /**
     * @return Parameter[]
     */
    public function getInstallationParameters(): array
    {
        return $this->installationParameters;
    }

    /**
     * @param Parameter $parameter
     *
     * @return static
     */
    public function addInstallationParameter(Parameter $parameter)
    {
        $this->installationParameters[] = $parameter;

        return $this;
    }

    /**
     * @param Parameter[] $parameters
     *
     * @return static
     */
    public function setInstallationParameters($parameters = [])
    {
        $this->installationParameters = $parameters;

        return $this;
    }

    /**
     * @return Parameter[]
     */
    public function getInstanceParameters(): array
    {
        return $this->instanceParameters;
    }

    /**
     * @param Parameter $parameter
     *
     * @return static
     */
    public function addInstanceParameter(Parameter $parameter)
    {
        $this->instanceParameters[] = $parameter;

        return $this;
    }

    /**
     * @param Parameter[] $parameters
     *
     * @return static
     */
    public function setInstanceParameters($parameters = [])
    {
        $this->instanceParameters = $parameters;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        $sourceType = \filter_var($this->source, \FILTER_VALIDATE_URL)
            ? 'src'
            : 'srcdoc';

        $extension = [
            'sys' => $this->sys,
            'extension' => [
                'name' => $this->name,
                'fieldTypes' => $this->fieldTypes,
                $sourceType => $this->source,
                'sidebar' => $this->sidebar,
            ],
        ];

        $parameters = [];
        if ($this->installationParameters) {
            $parameters['installation'] = $this->installationParameters;
        }
        if ($this->instanceParameters) {
            $parameters['instance'] = $this->instanceParameters;
        }

        if ($parameters) {
            $extension['extension']['parameters'] = $parameters;
        }

        return $extension;
    }
}
