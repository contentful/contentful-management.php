<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\E2E;

use Contentful\Link;
use Contentful\Management\Query;
use Contentful\Management\Resource\PublishedContentType;
use Contentful\Tests\End2EndTestCase;

class PublishedContentTypeTest extends End2EndTestCase
{
    /**
     * @vcr e2e_content_type_get_published_one.json
     */
    public function testGetPublishedContentType()
    {
        $manager = $this->getReadOnlySpaceManager();
        $contentType = $manager->getPublishedContentType('cat');

        $this->assertEquals('Cat', $contentType->getName());
        $this->assertEquals('name', $contentType->getDisplayField());
        $this->assertCount(8, $contentType->getFields());

        $sys = $contentType->getSystemProperties();
        $this->assertEquals('cat', $sys->getId());
        $this->assertEquals('ContentType', $sys->getType());
        $this->assertEquals(new \DateTimeImmutable('2013-06-27T22:46:12.852'), $sys->getCreatedAt());
        $this->assertEquals(new \DateTimeImmutable('2017-07-06T09:58:52.691'), $sys->getUpdatedAt());
        $this->assertEquals(new Link($this->readOnlySpaceId, 'Space'), $sys->getSpace());
        $this->assertEquals(8, $sys->getRevision());

        $json = '{"sys": {"space": {"sys": {"type": "Link","linkType": "Space","id": "cfexampleapi"}},"id": "cat","type": "ContentType","createdAt": "2013-06-27T22:46:12.852Z","updatedAt": "2017-07-06T09:58:52.691Z","revision": 8},"displayField": "name","name": "Cat","description": "Meow.","fields": [{"id": "name","name": "Name","type": "Text","localized": true,"required": true,"validations": [{"size": {"min": 3}}],"disabled": false,"omitted": false},{"id": "likes","name": "Likes","type": "Array","localized": false,"required": false,"disabled": false,"omitted": false,"items": {"type": "Symbol"}},{"id": "color","name": "Color","type": "Symbol","localized": false,"required": false,"disabled": false,"omitted": false},{"id": "bestFriend","name": "Best Friend","type": "Link","localized": false,"required": false,"disabled": false,"omitted": false,"linkType": "Entry"},{"id": "birthday","name": "Birthday","type": "Date","localized": false,"required": false,"disabled": false,"omitted": false},{"id": "lifes","name": "Lifes left","type": "Integer","localized": false,"required": false,"disabled": true,"omitted": false},{"id": "lives","name": "Lives left","type": "Integer","localized": false,"required": false,"disabled": false,"omitted": false},{"id": "image","name": "Image","type": "Link","localized": false,"required": false,"disabled": false,"omitted": false,"linkType": "Asset"}]}';
        $this->assertJsonStringEqualsJsonString($json, json_encode($contentType));
    }

    /**
     * @vcr e2e_content_type_get_published_collection.json
     */
    public function testGetPublishedContentTypes()
    {
        $manager = $this->getReadOnlySpaceManager();
        $contentTypes = $manager->getPublishedContentTypes();

        $this->assertInstanceOf(PublishedContentType::class, $contentTypes[0]);

        $query = (new Query())
            ->setLimit(1);
        $contentTypes = $manager->getPublishedContentTypes($query);
        $this->assertInstanceOf(PublishedContentType::class, $contentTypes[0]);
        $this->assertCount(1, $contentTypes);
    }
}
