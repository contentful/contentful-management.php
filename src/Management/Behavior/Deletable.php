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
 * Deletable interface.
 *
 * Represents a resource which can be deleted.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/assets/asset Deleting assets
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/content-types/content-type Deleting content types
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/entries/entry Deleting entries
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/locales/locale Deleting locales
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhooks/webhook Deleting webhooks
 */
interface Deletable extends SpaceScopedResourceInterface
{
}
