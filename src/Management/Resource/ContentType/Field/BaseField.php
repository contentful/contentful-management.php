<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource\ContentType\Field;

use Contentful\Management\Resource\ContentType\Validation\ValidationInterface;

/**
 * BaseField class.
 *
 * This class is the shared base for concrete field implementations.
 */
abstract class BaseField implements FieldInterface
{
    /**
     * ID of the Field.
     *
     * @var string
     */
    protected $id;

    /**
     * Name of the Field.
     *
     * @var string
     */
    protected $name;

    /**
     * Describes whether the Field is mandatory.
     *
     * @var bool
     */
    protected $required = false;

    /**
     * Describes whether the Field is localized.
     *
     * @var bool
     */
    protected $localized = false;

    /**
     * Describes whether the Field is disabled.
     *
     * @var bool
     */
    protected $disabled = false;

    /**
     * True if the field is omitted from CDA responses.
     *
     * @var bool
     */
    protected $omitted = false;

    /**
     * @var ValidationInterface[]
     */
    protected $validations = [];

    /**
     * BaseField constructor.
     *
     * @param string $id
     * @param string $name
     */
    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
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
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required === true;
    }

    /**
     * @param bool $required
     *
     * @return static
     */
    public function setRequired(bool $required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLocalized(): bool
    {
        return $this->localized === true;
    }

    /**
     * @param bool $localized
     *
     * @return static
     */
    public function setLocalized(bool $localized)
    {
        $this->localized = $localized;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->disabled === true;
    }

    /**
     * @param bool $disabled
     *
     * @return static
     */
    public function setDisabled(bool $disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function isOmitted(): bool
    {
        return $this->omitted === true;
    }

    /**
     * @param bool $omitted
     *
     * @return static
     */
    public function setOmitted(bool $omitted)
    {
        $this->omitted = $omitted;

        return $this;
    }

    /**
     * @return ValidationInterface[]
     */
    public function getValidations(): array
    {
        return $this->validations !== null ? $this->validations : [];
    }

    /**
     * @param ValidationInterface[] $validations
     *
     * @return static
     */
    public function setValidations(array $validations)
    {
        foreach ($validations as $validation) {
            if (!in_array($this->getType(), $validation::getValidFieldTypes())) {
                throw new \RuntimeException(sprintf(
                    'The validation "%s" can not be used for fields of type "%s".',
                    get_class($validation),
                    $this->getType()
                ));
            }
        }

        $this->validations = $validations;

        return $this;
    }

    /**
     * @param ValidationInterface $validation
     *
     * @return static
     */
    public function addValidation(ValidationInterface $validation)
    {
        if (!in_array($this->getType(), $validation::getValidFieldTypes())) {
            throw new \RuntimeException(sprintf(
                'The validation "%s" can not be used for fields of type "%s".',
                get_class($validation),
                $this->getType()
            ));
        }

        $this->validations[] = $validation;

        return $this;
    }

    /**
     * Returns an array to be used by "json_encode" to serialize objects of this class.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $data = [
            'name' => $this->name,
            'id' => $this->id,
            'type' => $this->getType(),
        ];

        if ($this->required !== null) {
            $data['required'] = $this->required;
        }

        if ($this->localized !== null) {
            $data['localized'] = $this->localized;
        }

        if ($this->disabled !== null) {
            $data['disabled'] = $this->disabled;
        }

        if ($this->omitted !== null) {
            $data['omitted'] = $this->omitted;
        }

        if (!empty($this->validations)) {
            $data['validations'] = $this->validations;
        }

        return $data;
    }
}
