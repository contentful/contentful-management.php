<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Field\Validation;

interface ValidationInterface extends \JsonSerializable
{
    public static function getValidFieldTypes(): array;

    public static function fromApiResponse(array $data): ValidationInterface;
}
