<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource;

/**
 * SpaceScopedResourceInterface.
 *
 * Represents a resource which is scoped to a Contentful space.
 * This interface should not be directly implemented by any class.
 * Its main use is to be extended by another interface.
 *
 * @see https://www.contentful.com/r/knowledgebase/spaces-and-organizations/
 */
interface SpaceScopedResourceInterface extends ResourceInterface
{
    /**
     * Returns the URI part that identifies a resource.
     *
     * @return string
     */
    public function getResourceUriPart(): string;
}
