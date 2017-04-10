<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Field\Validation;

class UniqueValidation implements ValidationInterface
{
    /**
     * UniqueValidation constructor.
     *
     * Empty constructor for forward compatibility
     */
    public function __construct()
    {
    }

    public static function fromApiResponse(array $data): ValidationInterface
    {
        return new self();
    }

    public static function getValidFieldTypes(): array
    {
        return ['Symbol', 'Integer', 'Number'];
    }

    public function jsonSerialize()
    {
        return (object) ["unique" => true];
    }
}
