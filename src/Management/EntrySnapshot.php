<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

/**
 * EntrySnapshot class.
 *
 * This class represents a resource with type "Snapshot" and entity type "Entry" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/entry-snapshots-collection
 * @see https://www.contentful.com/faq/versioning/
 */
class EntrySnapshot implements SpaceScopedResourceInterface
{
    /**
     * @var SystemProperties
     */
    private $sys;

    /**
     * @var Entry
     */
    private $entry;

    /**
     * EntrySnapshot constructor.
     */
    public function __construct()
    {
        $this->sys = new SystemProperties(['type' => 'Snapshot', 'snapshotEntityType' => 'Entry']);
    }

    /**
     * {@inheritDoc}
     */
    public function getSystemProperties(): SystemProperties
    {
        return $this->sys;
    }

    /**
     * {@inheritDoc}
     */
    public function getResourceUriPart(): string
    {
        return 'entries';
    }

    /**
     * @return Entry
     */
    public function getEntry(): Entry
    {
        return $this->entry;
    }

    /**
     * Returns an object to be used by `json_encode` to serialize objects of this class.
     *
     * @return object
     *
     * @see http://php.net/manual/en/jsonserializable.jsonserialize.php JsonSerializable::jsonSerialize
     */
    public function jsonSerialize()
    {
        return (object) [
            'snapshot' => $this->entry,
            'sys' => $this->sys,
        ];
    }
}
