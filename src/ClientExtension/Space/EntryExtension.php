<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\ClientExtension\Space;

use Contentful\Core\Resource\ResourceArray;
use Contentful\Management\Query;
use Contentful\Management\Resource\Entry as ResourceClass;

/**
 * EntryExtension trait.
 *
 * This extension is supposed to be applied to Client class which provides a `fetchResource` method.
 *
 * @method ResourceInterface|ResourceArray fetchResource(string $class, array $parameters, Query $query = null, ResourceInterface $resource = null)
 */
trait EntryExtension
{
    use Entry\EntrySnapshotExtension;

    /**
     * Returns an Entry resource.
     *
     * @param string $spaceId
     * @param string $entryId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/entries/entry
     */
    public function getEntry(string $spaceId, string $entryId): ResourceClass
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
            'entry' => $entryId,
        ]);
    }

    /**
     * Returns a ResourceArray object which contains Entry resources.
     *
     * @param string     $spaceId
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/entries/entries-collection
     */
    public function getEntries(string $spaceId, Query $query = null): ResourceArray
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
        ], $query);
    }
}
