<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Integration\Resource;

use Contentful\Management\ResourceBuilder;
use Contentful\Tests\Management\BaseTestCase;

class WebhookTest extends BaseTestCase
{
    /**
     * @expectedException        \RuntimeException
     * @expectedExceptionMessage Trying to build a filter object using invalid key "invalidKey".
     */
    public function testInvalidCreation()
    {
        (new ResourceBuilder())
            ->build($this->getParsedFixture('serialize.json'))
        ;
    }
}
