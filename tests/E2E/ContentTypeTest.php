<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\E2E;

use Contentful\Link;
use Contentful\Management\ContentType;
use Contentful\Management\Field\TextField;
use Contentful\Management\Field\Validation\SizeValidation;
use Contentful\Management\Query;
use Contentful\Tests\End2EndTestCase;

class ContentTypeTest extends End2EndTestCase
{
    /**
     * @vcr e2e_content_type_get.json
     */
    public function testGetContentType()
    {
        $manager = $this->getReadOnlySpaceManager();

        $contentType = $manager->getContentType('cat');

        $sys = $contentType->getSystemProperties();
        $this->assertEquals('cat', $sys->getId());
        $this->assertEquals('ContentType', $sys->getType());
        $this->assertEquals(29, $sys->getVersion());
        $this->assertEquals(new Link($this->readOnlySpaceId, 'Space'), $sys->getSpace());
        $this->assertEquals(new \DateTimeImmutable('2013-06-27T22:46:10.704'), $sys->getCreatedAt());
        $this->assertEquals(new \DateTimeImmutable('2016-11-21T15:01:43.873'), $sys->getUpdatedAt());
        $this->assertEquals(new Link('7BslKh9TdKGOK41VmLDjFZ', 'User'), $sys->getCreatedBy());
        $this->assertEquals(new Link('0PCYk22mt1xD7gTKZhHycN', 'User'), $sys->getUpdatedBy());
        $this->assertEquals(new Link('0PCYk22mt1xD7gTKZhHycN', 'User'), $sys->getPublishedBy());
        $this->assertEquals(28, $sys->getPublishedVersion());
        $this->assertEquals(6, $sys->getPublishedCounter());
        $this->assertEquals(new \DateTimeImmutable('2016-11-21T15:01:43.86'), $sys->getPublishedAt());
        $this->assertEquals(new \DateTimeImmutable('2013-06-27T22:46:12.852'), $sys->getFirstPublishedAt());

        $this->assertEquals('Cat', $contentType->getName());
        $this->assertEquals('Meow.', $contentType->getDescription());

        $fields = $contentType->getFields();
        $this->assertCount(8, $fields);

        $field0 = $fields[0];
        $this->AssertInstanceof(TextField::class, $field0);
        $this->assertEquals('name', $field0->getId());
        $this->assertEquals('Name', $field0->getName());
        $this->assertTrue($field0->isRequired());
        $this->assertTrue($field0->isLocalized());
        $this->assertFalse($field0->isDisabled());
        $this->assertFalse($field0->isOmitted());

        $field0validations = $field0->getValidations();
        $this->assertCount(1, $field0validations);

        $field0validation0 = $field0validations[0];
        $this->assertInstanceOf(SizeValidation::class, $field0validation0);
        $this->assertEquals(3, $field0validation0->getMin());

        $json = '{"name":"Cat","fields": [{"id":"name","name":"Name","type":"Text","required": true,"localized": true,"validations": [{"size": {"min": 3}}]},{"id":"likes","name":"Likes","type":"Array","required": false,"localized": false,"items": {"type":"Symbol"}},{"id":"color","name":"Color","type":"Symbol","required": false,"localized": false},{"id":"bestFriend","name":"Best Friend","type":"Link","required": false,"localized": false,"linkType":"Entry"},{"id":"birthday","name":"Birthday","type":"Date","required": false,"localized": false},{"id":"lifes","name":"Lifes left","type":"Integer","required": false,"localized": false,"disabled": true,"omitted": false},{"id":"lives","name":"Lives left","type":"Integer","required": false,"localized": false},{"id":"image","name":"Image","required": false,"localized": false,"type":"Link","linkType":"Asset"}],"displayField":"name","description":"Meow.","sys": {"id":"cat","type":"ContentType","space": {"sys": {  "type":"Link",  "linkType":"Space",  "id":"cfexampleapi" }},"createdAt":"2013-06-27T22:46:10.704Z","createdBy": {"sys": {  "type":"Link",  "linkType":"User",  "id":"7BslKh9TdKGOK41VmLDjFZ" }},"firstPublishedAt":"2013-06-27T22:46:12.852Z","publishedCounter": 6,"publishedAt":"2016-11-21T15:01:43.860Z","publishedBy": {"sys": {  "type":"Link",  "linkType":"User",  "id":"0PCYk22mt1xD7gTKZhHycN" }},"publishedVersion": 28,"version": 29,"updatedAt":"2016-11-21T15:01:43.873Z","updatedBy": {"sys":{"type":"Link","linkType":"User","id":"0PCYk22mt1xD7gTKZhHycN"}}}}';
        $this->assertJsonStringEqualsJsonString($json, json_encode($contentType));
    }

    /**
     * @vcr e2e_content_type_get_collection.json
     */
    public function testGetContentTypes()
    {
        $manager = $this->getReadOnlySpaceManager();
        $contentTypes = $manager->getContentTypes();

        $this->assertInstanceOf(ContentType::class, $contentTypes[0]);

        $query = (new Query)
            ->setLimit(1);
        $contentTypes = $manager->getContentTypes($query);
        $this->assertInstanceOf(ContentType::class, $contentTypes[0]);
        $this->assertCount(1, $contentTypes);
    }

    /**
     * @vcr e2e_content_type_create_update_activate_delete.json
     */
    public function testCreateUpdateActivateDeleteContentType()
    {
        $manager = $this->getReadWriteSpaceManager();

        $contentType = (new ContentType('Test CT'))
            ->setDescription('THE best content type');

        $manager->create($contentType);
        $this->assertNotNull($contentType->getSystemProperties()->getId());

        $contentType->setName('Test CT - Updates');
        $manager->update($contentType);

        $manager->publish($contentType);
        $this->assertEquals(1, $contentType->getSystemProperties()->getPublishedCounter());
        $this->assertEquals(2, $contentType->getSystemProperties()->getPublishedVersion());

        $manager->unpublish($contentType);
        $this->assertNull($contentType->getSystemProperties()->getPublishedVersion());

        $manager->delete($contentType);
    }

    /**
     * @vcr e2e_content_type_create_with_id.json
     */
    public function testCreateContentTypeWithGivenId()
    {
        $manager = $this->getReadWriteSpaceManager();

        $contentType = (new ContentType('Test CT'))
            ->setDescription('This content type will have `myCustomTestCt` as ID');

        $manager->create($contentType, 'myCustomTestCt');
        $this->assertEquals('myCustomTestCt', $contentType->getSystemProperties()->getId());

        $manager->delete($contentType);
    }
}
