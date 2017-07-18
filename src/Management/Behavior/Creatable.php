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
 * Creatable interface.
 *
 * Represents a resource which can be created.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/assets/asset Creating assets
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/content-types/content-type Creating content types
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/entries/entry Creating entries
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/locales/locale Creating locales
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhooks/webhook Creating webhooks
 */
interface Creatable extends SpaceScopedResourceInterface
{
}
