<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\ContentType\Field;

/**
 * SymbolField class.
 */
class SymbolField extends BaseField
{
    public function getType(): string
    {
        return 'Symbol';
    }
}
