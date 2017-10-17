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
 * EntrySnapshot class.
 *
 * This class represents a resource with type "Snapshot" and entity type "Entry" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/entry-snapshots-collection
 * @see https://www.contentful.com/faq/versioning/
 */
class EntrySnapshot extends Snapshot
{
    /**
     * @return Entry
     */
    public function getEntry(): Entry
    {
        return $this->snapshot;
    }
}
