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
 * RegexpValidation class.
 *
 * Takes a string that reflects a Javascript regex and flags.
 *
 * Applicable to:
 * - Symbol
 * - Text
 */
class RegexpValidation implements ValidationInterface
{
    /**
     * @var string|null
     */
    private $pattern;

    /**
     * @var string|null
     */
    private $flags;

    /**
     * RegexpValidation constructor.
     *
     * @param string|null $pattern
     * @param string|null $flags
     */
    public function __construct(string $pattern = null, string $flags = null)
    {
        $this->pattern = $pattern;
        $this->flags = $flags;
    }

    /**
     * @return string|null
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * @param string|null $flags
     */
    public function setFlags(string $flags = null)
    {
        $this->flags = $flags;
    }

    /**
     * @return string|null
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param string|null $pattern
     */
    public function setPattern(string $pattern = null)
    {
        $this->pattern = $pattern;
    }

    /**
     * {@inheritdoc}
     */
    public static function getValidFieldTypes(): array
    {
        return ['Text', 'Symbol'];
    }

    /**
     * Returns an array to be used by "json_encode" to serialize objects of this class.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $data = [];
        if ($this->pattern !== null) {
            $data['pattern'] = $this->pattern;
        }
        if ($this->flags !== null) {
            $data['flags'] = $this->flags;
        }

        return [
            'regexp' => $data,
        ];
    }
}
