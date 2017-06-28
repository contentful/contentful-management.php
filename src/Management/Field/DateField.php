<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Field;

class DateField extends AbstractField
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return 'Date';
    }
}
