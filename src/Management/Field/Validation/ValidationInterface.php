<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Field\Validation;

interface ValidationInterface extends \JsonSerializable
{
    public static function getValidFieldTypes(): array;

    public static function fromApiResponse(array $data): ValidationInterface;
}
