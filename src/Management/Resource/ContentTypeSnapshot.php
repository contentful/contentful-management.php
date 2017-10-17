<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Resource;

/**
 * ContentTypeSnapshot class.
 *
 * This class represents a resource with type "Snapshot" and entity type "ContentType" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/content-type-snapshots-collection
 * @see https://www.contentful.com/faq/versioning/
 */
class ContentTypeSnapshot extends Snapshot
{
    /**
     * @return ContentType
     */
    public function getContentType(): ContentType
    {
        return $this->snapshot;
    }
}
