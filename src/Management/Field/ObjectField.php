<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Field;

class ObjectField extends AbstractField
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return 'Object';
    }
}
