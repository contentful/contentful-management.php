<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

/**
 * SpaceScopedResourceInterface.
 *
 * Represents a resource which is scoped to a Contentful space.
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
    public function getResourceUrlPart(): string;
}
