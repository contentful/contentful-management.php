<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource\ContentType\Validation;

/**
 * ValidationInterface.
 */
interface ValidationInterface extends \JsonSerializable
{
    /**
     * Returns an array of allowed field types for the current validation.
     *
     * @return string[]
     */
    public static function getValidFieldTypes(): array;

    /**
     * Returns a ValidationInterface implementation created from data from the API.
     *
     * @param array $data
     *
     * @return ValidationInterface
     */
    public static function fromApiResponse(array $data): ValidationInterface;
}
