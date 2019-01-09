<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\ContentType\Field;

use Contentful\Management\Resource\ContentType\Validation\ValidationInterface;

/**
 * FieldInterface.
 */
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
     * @return static
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
     * @return static
     */
    public function setRequired(bool $required);

    /**
     * @return bool
     */
    public function isLocalized(): bool;

    /**
     * @param bool $localized
     *
     * @return static
     */
    public function setLocalized(bool $localized);

    /**
     * @return bool
     */
    public function isDisabled(): bool;

    /**
     * @param bool $disabled
     *
     * @return static
     */
    public function setDisabled(bool $disabled);

    /**
     * @return bool
     */
    public function isOmitted(): bool;

    /**
     * @param bool $omitted
     *
     * @return static
     */
    public function setOmitted(bool $omitted);

    /**
     * @return ValidationInterface[]
     */
    public function getValidations(): array;

    /**
     * @param ValidationInterface[] $validations
     *
     * @return static
     */
    public function setValidations(array $validations);

    /**
     * @param ValidationInterface $validation
     *
     * @return static
     */
    public function addValidation(ValidationInterface $validation);
}
