<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Field\Validation;

class SizeValidation implements ValidationInterface
{
    /**
     * @var int|null
     */
    private $min;

    /**
     * @var int|null
     */
    private $max;

    /**
     * SizeValidation constructor.
     *
     * @param int|null $min
     * @param int|null $max
     */
    public function __construct($min = null, $max = null)
    {
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * @return int|null
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @param int|null $min
     *
     * @return $this
     */
    public function setMin(int $min = null)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @param int|null $max
     *
     * @return $this
     */
    public function setMax(int $max = null)
    {
        $this->max = $max;

        return $this;
    }

    public static function getValidFieldTypes(): array
    {
        return ['Array', 'Text', 'Symbol'];
    }

    public static function fromApiResponse(array $data): ValidationInterface
    {
        $values = $data['size'];

        $min = $values['min'] ?? null;
        $max = $values['max'] ?? null;

        return new self($min, $max);
    }

    public function jsonSerialize()
    {
        $data = [];
        if ($this->min !== null) {
            $data['min'] = $this->min;
        }
        if ($this->max !== null) {
            $data['max'] = $this->max;
        }

        return (object) [
            'size' => (object) $data
        ];
    }
}
