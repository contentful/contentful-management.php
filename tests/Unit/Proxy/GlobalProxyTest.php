<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Proxy;

use Contentful\Tests\Management\BaseTestCase;
use Contentful\Tests\Management\Implementation\FakeGlobalProxy;

class GlobalProxyTest extends BaseTestCase
{
    public function testGetData()
    {
        $proxy = new FakeGlobalProxy();

        $this->assertSame([], $proxy->getProxyParameters());
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Can not convert a global proxy to a resource
     */
    public function testCannotConvertToResource()
    {
        (new FakeGlobalProxy())
            ->toResource()
        ;
    }
}
