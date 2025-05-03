<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Integration\Resource;

use Contentful\Management\ResourceBuilder;
use Contentful\Tests\Management\BaseTestCase;

class WebhookTest extends BaseTestCase
{
    public function testInvalidCreation()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Trying to build a filter object using invalid key "invalidKey".');

        (new ResourceBuilder())
            ->build($this->getParsedFixture('serialize.json'))
        ;
    }
}
