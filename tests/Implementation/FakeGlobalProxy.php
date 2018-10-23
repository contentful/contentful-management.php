<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Implementation;

use Contentful\Management\Proxy\GlobalProxy;

class FakeGlobalProxy extends GlobalProxy
{
    public function __construct()
    {
    }

    public function getProxyParameters(): array
    {
        return parent::getProxyParameters();
    }
}
