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
 * DeletableTrait.
 *
 * This trait is supposed to be applied to resources that can be deleted.
 *
 * @property ProxyInterface $proxy
 */
trait DeletableTrait
{
    /**
     * Deletes the current resource.
     */
    public function delete()
    {
        $this->proxy->delete($this);
    }
}
