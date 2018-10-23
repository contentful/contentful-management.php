<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\Behavior;

use Contentful\Management\Proxy\ProxyInterface;

/**
 * ArchivableTrait.
 *
 * This trait is supposed to be applied to resources that can be archived.
 *
 * @property ProxyInterface $proxy
 */
trait ArchivableTrait
{
    /**
     * Archives the current resource.
     */
    public function archive()
    {
        $this->proxy->archive($this);
    }

    /**
     * Unarchives the current resource.
     */
    public function unarchive()
    {
        $this->proxy->unarchive($this);
    }
}
