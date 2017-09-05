<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Integration\Management\Resource;

use Contentful\Management\Client;
use Contentful\Management\Proxy\BaseProxy;
use Contentful\Management\Resource\BaseResource;
use PHPUnit\Framework\TestCase;

class BaseResourceTest extends TestCase
{
    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Trying to call invalid method "fakeMethod" on resource of type "Contentful\Tests\Integration\Management\Resource\FakeResource" which forwards to proxy "Contentful\Tests\Integration\Management\Resource\FakeProxy".
     */
    public function testInvalidProxiedAction()
    {
        $fakeResource = new FakeResource();
        $proxy = new FakeProxy(new Client('fakeToken'));
        $fakeResource->setProxy($proxy);

        $fakeResource->fakeMethod();
    }
}

class FakeProxy extends BaseProxy
{
    protected $requiresSpaceId = false;

    protected function getResourceUri(array $values): string
    {
        return '';
    }

    public function getEnabledMethods(): array
    {
        return [];
    }
}

class FakeResource extends BaseResource
{
    public function __construct()
    {
        parent::__construct('FakeResource');
    }

    public function jsonSerialize(): array
    {
        return ['sys' => []];
    }
}
