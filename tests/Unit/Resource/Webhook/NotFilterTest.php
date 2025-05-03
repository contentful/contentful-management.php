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
use Contentful\Management\Resource\Webhook\NotFilter;
use Contentful\Tests\Management\BaseTestCase;

class NotFilterTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $equalityFilter = new EqualityFilter('sys.environment.sys.id', 'staging');
        $filter = new NotFilter($equalityFilter);

        $this->assertSame($equalityFilter, $filter->getChild());

        $equalityFilter = new EqualityFilter('sys.contentType.sys.id', 'blogPost');
        $filter->setChild($equalityFilter);
        $this->assertSame($equalityFilter, $filter->getChild());
    }

    public function testJsonSerialize()
    {
        $filter = new NotFilter(new EqualityFilter('sys.environment.sys.id', 'staging'));

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/Webhook/NotFilter/serialize.json', $filter);
    }
}
