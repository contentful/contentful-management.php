<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\Webhook;

use Contentful\Management\Resource\Webhook\EqualityFilter;
use Contentful\Tests\Management\BaseTestCase;

class EqualityFilterTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $filter = new EqualityFilter('sys.environment.sys.id', 'staging');

        $this->assertSame('sys.environment.sys.id', $filter->getDoc());
        $this->assertSame('staging', $filter->getValue());

        $filter->setDoc('sys.contentType.sys.id');
        $filter->setValue('blogPost');

        $this->assertSame('sys.contentType.sys.id', $filter->getDoc());
        $this->assertSame('blogPost', $filter->getValue());
    }

    public function testJsonSerialize()
    {
        $filter = new EqualityFilter('sys.environment.sys.id', 'staging');

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/Webhook/EqualityFilter/serialize.json', $filter);
    }
}
