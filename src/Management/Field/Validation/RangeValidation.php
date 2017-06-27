<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Field\Validation;

class RangeValidation implements ValidationInterface
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
     * RangeValidation constructor.
     *
     * @param int|null $min
     * @param int|null $max
     */
    public function __construct(int $min = null, int $max = null)
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
        return ['Number', 'Integer'];
    }

    public static function fromApiResponse(array $data): ValidationInterface
    {
        $values = $data['range'];

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
            'range' => (object) $data
        ];
    }
}
