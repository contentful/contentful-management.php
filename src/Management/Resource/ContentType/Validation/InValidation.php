<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Resource\ContentType\Validation;

/**
 * InValidation class.
 *
 * Takes an array of values and validates that the field value is in this array.
 *
 * Applicable to:
 * - Symbol
 * - Text
 * - Integer
 * - Number
 */
class InValidation implements ValidationInterface
{
    /**
     * @var string[]
     */
    private $values = [];

    /**
     * InValidation constructor.
     *
     * @param string[] $values
     */
    public function __construct(array $values = [])
    {
        $this->values = $values;
    }

    /**
     * @return string[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param string[] $values
     *
     * @return static
     */
    public function setValues(array $values)
    {
        $this->values = $values;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function getValidFieldTypes(): array
    {
        return ['Text', 'Symbol', 'Integer', 'Number'];
    }

    /**
     * Returns an array to be used by "json_encode" to serialize objects of this class.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'in' => $this->values,
        ];
    }
}
