<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Field;

class TextField extends AbstractField
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return 'Text';
    }
}