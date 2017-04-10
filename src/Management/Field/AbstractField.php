<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Field;

use Contentful\Management\Field\Validation\ValidationInterface;

abstract class AbstractField implements FieldInterface
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
     * @return $this
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
     * @return $this;
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    public function setValidations(array $validations)
    {
        foreach ($validations as $validation) {
            if (!in_array($this->getType(), $validation::getValidFieldTypes())) {
                throw new \RuntimeException('The validation ' . get_class($validation) . ' can not be used for fields of type ' . $this->getType() . '.');
            }
        }

        $this->validations = $validations;

        return $this;
    }

    /**
     * @param  ValidationInterface $validation
     *
     * @return $this
     */
    public function addValidation(ValidationInterface $validation)
    {
        if (!in_array($this->getType(), $validation::getValidFieldTypes())) {
            throw new \RuntimeException('The validation ' . get_class($validation) . ' can not be used for fields of type ' . $this->getType() . '.');
        }

        $this->validations[] = $validation;

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
        $data = [
            'name' => $this->name,
            'id' => $this->id,
            'type' => $this->getType(),
            'required' => $this->required,
            'localized' => $this->localized
        ];

        if ($this->disabled !== null) {
            $data['disabled'] = $this->disabled;
        }

        if ($this->omitted !== null) {
            $data['omitted'] = $this->omitted;
        }

        if (!empty($this->validations)) {
            $data['validations'] = $this->validations;
        }

        return (object) $data;
    }
}
