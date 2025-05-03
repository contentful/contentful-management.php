<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\Webhook;

use Contentful\Management\Resource\Webhook\InclusionFilter;
use Contentful\Tests\Management\BaseTestCase;

class InclusionFilterTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $filter = new InclusionFilter('sys.environment.sys.id', ['master', 'staging']);

        $this->assertSame('sys.environment.sys.id', $filter->getDoc());
        $this->assertSame(['master', 'staging'], $filter->getValues());

        $filter->setDoc('sys.contentType.sys.id');
        $filter->setValues(['blogPost']);

        $this->assertSame('sys.contentType.sys.id', $filter->getDoc());
        $this->assertSame(['blogPost'], $filter->getValues());
    }

    public function testJsonSerialize()
    {
        $filter = new InclusionFilter('sys.environment.sys.id', ['master', 'staging']);

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/Webhook/InclusionFilter/serialize.json', $filter);
    }
}
