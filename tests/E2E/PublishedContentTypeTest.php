<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Link;
use Contentful\Management\ApiDateTime;
use Contentful\Management\Query;
use Contentful\Management\Resource\ContentType;
use Contentful\Tests\Management\BaseTestCase;

class PublishedContentTypeTest extends BaseTestCase
{
    /**
     * @vcr e2e_content_type_get_published_one.json
     */
    public function testGetPublishedContentType()
    {
        $client = $this->getDefaultClient();
        $contentType = $client->publishedContentType->get('cat');

        $this->assertEquals('Cat', $contentType->getName());
        $this->assertEquals('name', $contentType->getDisplayField());
        $this->assertEquals(new Link('cat', 'ContentType'), $contentType->asLink());
        $this->assertEquals(true, $contentType->isPublished());
        $this->assertCount(8, $contentType->getFields());

        $sys = $contentType->getSystemProperties();
        $this->assertEquals('cat', $sys->getId());
        $this->assertEquals('ContentType', $sys->getType());
        $this->assertEquals(new ApiDateTime('2017-10-17T12:23:46.365'), $sys->getCreatedAt());
        $this->assertEquals(new ApiDateTime('2017-10-17T12:23:46.365'), $sys->getUpdatedAt());
        $this->assertEquals(new Link($this->defaultSpaceId, 'Space'), $sys->getSpace());
        $this->assertEquals(1, $sys->getRevision());
    }

    /**
     * @vcr e2e_content_type_get_published_collection.json
     */
    public function testGetPublishedContentTypes()
    {
        $client = $this->getDefaultClient();
        $contentTypes = $client->publishedContentType->getAll();

        $this->assertInstanceOf(ContentType::class, $contentTypes[0]);

        $query = (new Query())
            ->setLimit(1);
        $contentTypes = $client->publishedContentType->getAll($query);
        $this->assertInstanceOf(ContentType::class, $contentTypes[0]);
        $this->assertCount(1, $contentTypes);
    }
}
