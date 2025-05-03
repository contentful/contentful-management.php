<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Implementation;

use Contentful\Management\Resource\Entry;

class FantasticCreatureEntry extends Entry
{
    /**
     * @return string|null
     */
    public function getName(string $locale = 'en-US')
    {
        return $this->getField('name', $locale);
    }
}
