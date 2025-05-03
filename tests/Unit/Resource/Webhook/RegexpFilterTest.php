<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\Webhook;

use Contentful\Management\Resource\Webhook\RegexpFilter;
use Contentful\Tests\Management\BaseTestCase;

class RegexpFilterTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $filter = new RegexpFilter('sys.environment.sys.id', '^ci-.+$');

        $this->assertSame('sys.environment.sys.id', $filter->getDoc());
        $this->assertSame('^ci-.+$', $filter->getPattern());

        $filter->setDoc('sys.contentType.sys.id');
        $filter->setPattern('^blogPost-.+$');

        $this->assertSame('sys.contentType.sys.id', $filter->getDoc());
        $this->assertSame('^blogPost-.+$', $filter->getPattern());
    }

    public function testJsonSerialize()
    {
        $filter = new RegexpFilter('sys.environment.sys.id', '^ci-.+$');

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/Webhook/RegexpFilter/serialize.json', $filter);
    }
}
