<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

/**
 * Updatable interface.
 *
 * Represents an entity which can be updated.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/assets/asset Updating assets
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/content-types/content-type Updating content types
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/entries/entry Updagint entries
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/locales/locale Updating locales
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhooks/webhook Updating webhooks
 */
interface Updatable extends SpaceScopedResourceInterface
{
}
