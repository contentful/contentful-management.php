<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Behavior;

use Contentful\Management\Resource\SpaceScopedResourceInterface;

/**
 * Archivable interface.
 *
 * Represents a resource which can be archived.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/assets/asset-archiving Archiving assets
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/entries/entry-archiving Archiving entries
 */
interface Archivable extends SpaceScopedResourceInterface
{
}
