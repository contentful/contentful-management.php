<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
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
    public function getId(): string;

    public function getName(): string;

    /**
     * @return static
     */
    public function setName(string $name);

    public function getType(): string;

    public function isRequired(): bool;

    /**
     * @return static
     */
    public function setRequired(bool $required);

    public function isLocalized(): bool;

    /**
     * @return static
     */
    public function setLocalized(bool $localized);

    public function isDisabled(): bool;

    /**
     * @return static
     */
    public function setDisabled(bool $disabled);

    public function isOmitted(): bool;

    /**
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
     * @return static
     */
    public function addValidation(ValidationInterface $validation);
}
