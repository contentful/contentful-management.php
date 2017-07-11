<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

use Contentful\Management\Resource\SpaceScopedResourceInterface;

/**
 * Publishable interface.
 *
 * Represents a resource which can be published.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/assets/asset-publishing Publishing assets
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/content-types/content-type-activation Publishing content types
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/entries/entry-publishing Publishing entries
 */
interface Publishable extends SpaceScopedResourceInterface
{
}
