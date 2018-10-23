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
 * PublishableTrait.
 *
 * This trait is supposed to be applied to resources that can be published.
 *
 * @property ProxyInterface $proxy
 */
trait PublishableTrait
{
    /**
     * Publishes the current resource.
     */
    public function publish()
    {
        $this->proxy->publish($this);
    }

    /**
     * Unpublishes the current resource.
     */
    public function unpublish()
    {
        $this->proxy->unpublish($this);
    }
}
