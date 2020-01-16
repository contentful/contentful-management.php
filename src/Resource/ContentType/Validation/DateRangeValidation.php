<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\ContentType\Validation;

/**
 * DateRangeValidation class.
 *
 * Defines a minimum and maximum date range.
 *
 * Applicable to:
 * - Date
 */
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
     * @return static
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
     * @return static
     */
    public function setMax(string $max = null)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function getValidFieldTypes(): array
    {
        return ['Date'];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        $data = [];
        if (null !== $this->min) {
            $data['min'] = $this->min;
        }
        if (null !== $this->max) {
            $data['max'] = $this->max;
        }

        return [
            'dateRange' => $data,
        ];
    }
}
