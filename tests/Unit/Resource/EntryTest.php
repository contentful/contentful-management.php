<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource;

use Contentful\Core\Api\DateTimeImmutable;
use Contentful\Management\Resource\Entry;
use Contentful\Tests\Management\BaseTestCase;
use PHPUnit\Framework\Error\Error;

class EntryTest extends BaseTestCase
{
    public function testInvalidCallMethod()
    {
        $this->expectException(Error::class);

        $entry = new Entry('blogPost');

        $entry->invalidAction();
    }

    public function testJsonSerialize()
    {
        $entry = (new Entry('blogPost'))
            ->setField('title', 'en-US', 'My summer holidays')
            ->setField('publishedAt', 'en-US', new DateTimeImmutable('2017-01-01 16:30:00'))
            ->setField('tags', 'en-US', ['italy', 'venice', 'rome', 'sicily'])
        ;

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/entry.json', $entry);
    }
}
