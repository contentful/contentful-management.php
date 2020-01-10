<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\Extension;

class Parameter implements \JsonSerializable
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var bool
     */
    protected $required = false;

    /**
     * @var string|int|bool
     */
    protected $default;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var array<string, string>
     */
    protected $labels = [];

    /**
     * @var string[]
     */
    protected static $validTypes = [
        'Symbol',
        'Enum',
        'Number',
        'Boolean',
    ];

    /**
     * Parameter constructor.
     */
    public function __construct(string $id, string $name, string $type)
    {
        $this->setId($id)
            ->setName($name)
            ->setType($type)
        ;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return static
     */
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return static
     */
    public function setName(string $name)
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
     * @return static
     */
    public function setDescription(string $description = null)
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return static
     */
    public function setType(string $type)
    {
        if (!\in_array($type, self::$validTypes, true)) {
            throw new \InvalidArgumentException(\sprintf('Invalid type "%s" given for parameter with ID "%s".', $type, $this->id));
        }

        $this->type = $type;

        return $this;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @return static
     */
    public function setRequired(bool $required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * @return bool|int|string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param bool|int|string $default
     *
     * @return static
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return static
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    public function getLabels(): array
    {
        return $this->labels;
    }

    /**
     * @return static
     */
    public function setLabels(array $labels)
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return \array_filter([
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'required' => $this->required,
            'description' => $this->description,
            'default' => $this->default,
            'options' => $this->options,
            'labels' => $this->labels,
        ], function ($item) {
            return null !== $item && [] !== $item;
        });
    }
}
