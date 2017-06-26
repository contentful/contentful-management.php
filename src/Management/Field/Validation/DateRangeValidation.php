<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Field\Validation;

class DateRangeValidation implements ValidationInterface
{
    /**
     * @var string|null
     */
    private $min;

    /**
     * @var string|null
     */
    private $max;

    /**
     * RangeValidation constructor.
     *
     * @param string|null $min
     * @param string|null $max
     */
    public function __construct(string $min = null, string $max = null)
    {
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * @return string|null
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @param string|null $min
     *
     * @return $this
     */
    public function setMin(string $min = null)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @param string|null $max
     *
     * @return $this
     */
    public function setMax(string $max = null)
    {
        $this->max = $max;

        return $this;
    }

    public static function getValidFieldTypes(): array
    {
        return ['Date'];
    }

    public static function fromApiResponse(array $data): ValidationInterface
    {
        $values = $data['dateRange'];

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
            'dateRange' => (object) $data
        ];
    }
}
