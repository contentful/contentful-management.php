<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

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
}
