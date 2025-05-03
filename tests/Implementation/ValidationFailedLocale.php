<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Implementation;

use Contentful\Management\Resource\Locale;

class ValidationFailedLocale extends Locale
{
    public function asRequestBody(): string
    {
        return '{"name":"A cool locale"}';
    }
}
