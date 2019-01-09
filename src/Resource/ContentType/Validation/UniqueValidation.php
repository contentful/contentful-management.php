<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\ContentType\Validation;

/**
 * UniqueValidation class.
 *
 * Mandates that a value be unique among all entries with the same content type.
 *
 * Applicable to:
 * - Symbol
 * - Integer
 * - Number
 */
class UniqueValidation implements ValidationInterface
{
    /**
     * UniqueValidation constructor.
     */
    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function getValidFieldTypes(): array
    {
        return ['Symbol', 'Integer', 'Number'];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return [
            'unique' => \true,
        ];
    }
}
