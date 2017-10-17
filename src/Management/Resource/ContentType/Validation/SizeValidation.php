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
 * SizeValidation class.
 *
 * Takes optional min and max parameters and
 * validates the size of an array or a string.
 *
 * Applicable to:
 * - Array
 * - Symbol
 * - Text
 */
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
     * @return static
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
     * @return static
     */
    public function setMax(int $max = null)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function getValidFieldTypes(): array
    {
        return ['Array', 'Text', 'Symbol'];
    }

    /**
     * Returns an array to be used by "json_encode" to serialize objects of this class.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $data = [];
        if ($this->min !== null) {
            $data['min'] = $this->min;
        }
        if ($this->max !== null) {
            $data['max'] = $this->max;
        }

        return [
            'size' => $data,
        ];
    }
}
