<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Field\Validation;

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
     * @return $this
     */
    public function setValues(array $values)
    {
        $this->values = $values;

        return $this;
    }

    public static function getValidFieldTypes(): array
    {
        return ['Text', 'Symbol', 'Integer', 'Number'];
    }

    public static function fromApiResponse(array $data): ValidationInterface
    {
        $values = $data['in'];

        return new self($values);
    }

    public function jsonSerialize()
    {
        return (object) [
            'in' => $this->values
        ];
    }
}
