<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Behavior;

use Contentful\Link;
use Contentful\Management\Resource\ResourceInterface;

/**
 * Linkable interface.
 *
 * Represents a resource which can be represented as a Link.
 *
 * @see https://www.contentful.com/developers/docs/concepts/links/
 */
interface Linkable extends ResourceInterface
{
    /**
     * Creates a Link representation of the current resource.
     *
     * @return Link
     */
    public function asLink(): Link;
}
