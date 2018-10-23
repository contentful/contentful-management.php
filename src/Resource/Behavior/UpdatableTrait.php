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
 * UpdatableTrait.
 *
 * This trait is supposed to be applied to resources that can be updated.
 *
 * @property ProxyInterface $proxy
 */
trait UpdatableTrait
{
    /**
     * Updates the current resource.
     */
    public function update()
    {
        $this->proxy->update($this);
    }
}
