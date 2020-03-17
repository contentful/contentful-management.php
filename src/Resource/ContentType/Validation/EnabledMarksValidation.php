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
 * EnableMarksValidation class stub.
 */
class EnabledMarksValidation implements ValidationInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getValidFieldTypes(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [];
    }
}
