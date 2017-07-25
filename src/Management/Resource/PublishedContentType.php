<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource;

/**
 * PublishedContentType class.
 *
 * This class represents a resource with type "ContentType" (which was already published) in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/content-types/activated-content-type-collection
 */
class PublishedContentType extends ContentType
{
    /**
     * {@inheritdoc}
     */
    public function isPublished(): bool
    {
        return true;
    }
}
