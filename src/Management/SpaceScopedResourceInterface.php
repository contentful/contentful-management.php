<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

interface SpaceScopedResourceInterface extends ResourceInterface
{
    public function getResourceUrlPart(): string;
}
