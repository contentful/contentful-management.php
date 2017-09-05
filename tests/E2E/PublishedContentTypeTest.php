<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\E2E\Management;

use Contentful\Link;
use Contentful\Management\ApiDateTime;
use Contentful\Management\Query;
use Contentful\Management\Resource\ContentType;
use Contentful\Tests\End2EndTestCase;

class PublishedContentTypeTest extends End2EndTestCase
{
    /**
     * @vcr e2e_content_type_get_published_one.json
     */
    public function testGetPublishedContentType()
    {
        $client = $this->getReadOnlyClient();
        $contentType = $client->publishedContentType->get('cat');

        $this->assertEquals('Cat', $contentType->getName());
        $this->assertEquals('name', $contentType->getDisplayField());
        $this->assertEquals(new Link('cat', 'ContentType'), $contentType->asLink());
        $this->assertEquals(true, $contentType->isPublished());
        $this->assertCount(8, $contentType->getFields());

        $sys = $contentType->getSystemProperties();
        $this->assertEquals('cat', $sys->getId());
        $this->assertEquals('ContentType', $sys->getType());
        $this->assertEquals(new ApiDateTime('2013-06-27T22:46:12.852'), $sys->getCreatedAt());
        $this->assertEquals(new ApiDateTime('2017-07-06T09:58:52.691'), $sys->getUpdatedAt());
        $this->assertEquals(new Link($this->readOnlySpaceId, 'Space'), $sys->getSpace());
        $this->assertEquals(8, $sys->getRevision());
    }

    /**
     * @vcr e2e_content_type_get_published_collection.json
     */
    public function testGetPublishedContentTypes()
    {
        $client = $this->getReadOnlyClient();
        $contentTypes = $client->publishedContentType->getAll();

        $this->assertInstanceOf(ContentType::class, $contentTypes[0]);

        $query = (new Query())
            ->setLimit(1);
        $contentTypes = $client->publishedContentType->getAll($query);
        $this->assertInstanceOf(ContentType::class, $contentTypes[0]);
        $this->assertCount(1, $contentTypes);
    }
}
