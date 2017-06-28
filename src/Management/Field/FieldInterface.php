<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Field;

use Contentful\Management\Field\Validation\ValidationInterface;

interface FieldInterface extends \JsonSerializable
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name);

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return bool
     */
    public function isRequired(): bool;

    /**
     * @param bool $required
     *
     * @return $this;
     */
    public function setRequired(bool $required);

    /**
     * @return bool
     */
    public function isLocalized(): bool;

    /**
     * @param bool $localized
     *
     * @return $this
     */
    public function setLocalized(bool $localized);

    /**
     * @return bool
     */
    public function isDisabled(): bool;

    /**
     * @param bool $disabled
     *
     * @return $this
     */
    public function setDisabled(bool $disabled);

    /**
     * @return bool
     */
    public function isOmitted(): bool;

    /**
     * @param bool $omitted
     *
     * @return $this
     */
    public function setOmitted(bool $omitted);

    /**
     * @return ValidationInterface[]
     */
    public function getValidations(): array;

    /**
     * @param ValidationInterface[] $validations
     *
     * @return $this
     */
    public function setValidations(array $validations);

    /**
     * @param ValidationInterface $validation
     *
     * @return $this
     */
    public function addValidation(ValidationInterface $validation);
}
