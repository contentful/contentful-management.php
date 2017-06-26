<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

/**
 * Archivable interface.
 *
 * Represents an entity which can be archived.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/assets/asset-archiving Archiving assets
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/entries/entry-archiving Archiving entries
 */
interface Archivable extends SpaceScopedResourceInterface
{
}
