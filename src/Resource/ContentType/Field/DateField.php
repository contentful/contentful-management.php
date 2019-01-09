<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\ContentType\Field;

/**
 * DateField class.
 */
class DateField extends BaseField
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return 'Date';
    }
}
