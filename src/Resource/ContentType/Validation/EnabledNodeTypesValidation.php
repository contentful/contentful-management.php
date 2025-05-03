<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\ContentType\Validation;

/**
 * EnabledNodeTypesValidation class stub.
 */
class EnabledNodeTypesValidation implements ValidationInterface
{
    public static function getValidFieldTypes(): array
    {
        return [];
    }

    public function jsonSerialize(): mixed
    {
        return [];
    }
}
