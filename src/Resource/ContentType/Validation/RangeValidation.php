<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2022 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\ContentType\Validation;

/**
 * RangeValidation class.
 *
 * Takes optional min and max parameters and validates the range of a value.
 *
 * Applicable to:
 * - Integer
 * - Number
 */
class RangeValidation implements ValidationInterface
{
    /**
     * @var float|null
     */
    private $min;

    /**
     * @var float|null
     */
    private $max;

    /**
     * RangeValidation constructor.
     */
    public function __construct(float $min = null, float $max = null)
    {
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * @return float|null
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @return static
     */
    public function setMin(float $min = null)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @param int|null $max
     *
     * @return static
     */
    public function setMax(float $max = null)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function getValidFieldTypes(): array
    {
        return ['Number', 'Integer'];
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
            'range' => $data,
        ];
    }
}
