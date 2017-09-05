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
use Contentful\Management\Resource\ContentType\Field\ArrayField;
use Contentful\Management\Resource\ContentType\Field\BooleanField;
use Contentful\Management\Resource\ContentType\Field\DateField;
use Contentful\Management\Resource\ContentType\Field\IntegerField;
use Contentful\Management\Resource\ContentType\Field\LinkField;
use Contentful\Management\Resource\ContentType\Field\LocationField;
use Contentful\Management\Resource\ContentType\Field\NumberField;
use Contentful\Management\Resource\ContentType\Field\ObjectField;
use Contentful\Management\Resource\ContentType\Field\SymbolField;
use Contentful\Management\Resource\ContentType\Field\TextField;
use Contentful\Management\Resource\ContentType\Validation\AssetFileSizeValidation;
use Contentful\Management\Resource\ContentType\Validation\LinkMimetypeGroupValidation;
use Contentful\Management\Resource\ContentType\Validation\RangeValidation;
use Contentful\Management\Resource\ContentType\Validation\SizeValidation;
use Contentful\Tests\End2EndTestCase;

class ContentTypeTest extends End2EndTestCase
{
    /**
     * @vcr e2e_content_type_get_one.json
     */
    public function testGetContentType()
    {
        $client = $this->getReadOnlyClient();

        $contentType = $client->contentType->get('cat');

        $sys = $contentType->getSystemProperties();
        $this->assertEquals('cat', $sys->getId());
        $this->assertEquals('ContentType', $sys->getType());
        $this->assertEquals(33, $sys->getVersion());
        $this->assertEquals(new Link($this->readOnlySpaceId, 'Space'), $sys->getSpace());
        $this->assertEquals(new ApiDateTime('2013-06-27T22:46:10.704'), $sys->getCreatedAt());
        $this->assertEquals(new ApiDateTime('2017-07-06T09:58:52.710'), $sys->getUpdatedAt());
        $this->assertEquals(new Link('7BslKh9TdKGOK41VmLDjFZ', 'User'), $sys->getCreatedBy());
        $this->assertEquals(new Link('5wTIctqPekjOi9TGctNW7L', 'User'), $sys->getUpdatedBy());
        $this->assertEquals(new Link('5wTIctqPekjOi9TGctNW7L', 'User'), $sys->getPublishedBy());
        $this->assertEquals(32, $sys->getPublishedVersion());
        $this->assertEquals(8, $sys->getPublishedCounter());
        $this->assertEquals(new ApiDateTime('2017-07-06T09:58:52.691'), $sys->getPublishedAt());
        $this->assertEquals(new ApiDateTime('2013-06-27T22:46:12.852'), $sys->getFirstPublishedAt());

        $this->assertEquals('Cat', $contentType->getName());
        $this->assertEquals('Meow.', $contentType->getDescription());
        $this->assertEquals(new Link('cat', 'ContentType'), $contentType->asLink());
        $this->assertEquals(false, $contentType->isPublished());

        $fields = $contentType->getFields();
        $this->assertCount(8, $fields);

        $field0 = $fields[0];
        $this->assertInstanceof(TextField::class, $field0);
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

        $json = '{"name":"Cat","fields": [{"id":"name","name":"Name","type":"Text","required": true,"localized": true,"validations": [{"size": {"min": 3}}]},{"id":"likes","name":"Likes","type":"Array","required": false,"localized": false,"items": {"type":"Symbol"}},{"id":"color","name":"Color","type":"Symbol","required": false,"localized": false},{"id":"bestFriend","name":"Best Friend","type":"Link","required": false,"localized": false,"linkType":"Entry"},{"id":"birthday","name":"Birthday","type":"Date","required": false,"localized": false},{"id":"lifes","name":"Lifes left","type":"Integer","required": false,"localized": false,"disabled": true,"omitted": false},{"id":"lives","name":"Lives left","type":"Integer","required": false,"localized": false},{"id":"image","name":"Image","required": false,"localized": false,"type":"Link","linkType":"Asset"}],"displayField":"name","description":"Meow.","sys": {"id":"cat","type":"ContentType","space": {"sys": {  "type":"Link",  "linkType":"Space",  "id":"cfexampleapi" }},"createdAt":"2013-06-27T22:46:10.704Z","createdBy": {"sys": {  "type":"Link",  "linkType":"User",  "id":"7BslKh9TdKGOK41VmLDjFZ" }},"firstPublishedAt":"2013-06-27T22:46:12.852Z","publishedCounter": 8,"publishedAt":"2017-07-06T09:58:52.691Z","publishedBy": {"sys": {  "type":"Link",  "linkType":"User",  "id":"5wTIctqPekjOi9TGctNW7L" }},"publishedVersion": 32,"version": 33,"updatedAt":"2017-07-06T09:58:52.710Z","updatedBy": {"sys":{"type":"Link","linkType":"User","id":"5wTIctqPekjOi9TGctNW7L"}}}}';
        $this->assertJsonStringEqualsJsonString($json, json_encode($contentType));
    }

    /**
     * @vcr e2e_content_type_get_collection.json
     */
    public function testGetContentTypes()
    {
        $client = $this->getReadOnlyClient();
        $contentTypes = $client->contentType->getAll();

        $this->assertInstanceOf(ContentType::class, $contentTypes[0]);

        $query = (new Query())
            ->setLimit(1);
        $contentTypes = $client->contentType->getAll($query);
        $this->assertInstanceOf(ContentType::class, $contentTypes[0]);
        $this->assertCount(1, $contentTypes);
    }

    /**
     * @vcr e2e_content_type_create_update_activate_delete.json
     */
    public function testCreateUpdateActivateDeleteContentType()
    {
        $client = $this->getReadWriteClient();

        $contentType = (new ContentType('Test CT'))
            ->setDescription('THE best content type');

        $client->contentType->create($contentType);
        $this->assertNotNull($contentType->getId());

        $contentType->setName('Test CT - Updates');
        $contentType->update();

        $contentType->publish();
        $this->assertEquals(1, $contentType->getSystemProperties()->getPublishedCounter());
        $this->assertEquals(2, $contentType->getSystemProperties()->getPublishedVersion());

        $contentType->unpublish();
        $this->assertNull($contentType->getSystemProperties()->getPublishedVersion());

        $contentType->delete();
    }

    /**
     * @vcr e2e_content_type_create_with_id.json
     */
    public function testCreateContentTypeWithGivenId()
    {
        $client = $this->getReadWriteClient();

        $contentType = (new ContentType('Test CT'))
            ->setDescription('This content type will have `myCustomTestCt` as ID');

        $client->contentType->create($contentType, 'myCustomTestCt');
        $this->assertEquals('myCustomTestCt', $contentType->getId());

        $contentType->delete();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Trying to access invalid field "invalidField" on content type "bookmark".
     * @vcr e2e_content_type_invalid_field_access.json
     */
    public function testInvalidFieldAccess()
    {
        $contentType = $this->getReadWriteClient()->contentType->get('bookmark');

        $contentType->getField('invalidField');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid items type "invalidItemsLink". Valid values are Symbol, Link.
     */
    public function testInvalidItemsLink()
    {
        new ArrayField('arrayField', 'Array Field', 'invalidItemsLink');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid items link type "invalidItemsLinkType". Valid values are Asset, Entry.
     */
    public function testInvalidItemsLinkType()
    {
        new ArrayField('arrayField', 'Array Field', 'Link', 'invalidItemsLinkType');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The validation "Contentful\Management\Resource\ContentType\Validation\RangeValidation" can not be used for fields of type "Link".
     */
    public function testInvalidItemsValidation()
    {
        $field = new ArrayField('arrayField', 'Array Field', 'Link', 'Asset');

        $field->addItemsValidation(new RangeValidation(0, 50));
    }

    /**
     * @vcr e2e_content_type_full_fields.json
     */
    public function testContentTypeFields()
    {
        $client = $this->getReadWriteClient();

        $contentType = new ContentType('fullContentType');
        $contentType->setName('Full Content Type');
        $contentType->setDescription('This content type includes all field types');

        $field = new ArrayField('arrayField', 'Array Field', 'Link', 'Asset');
        $field->setItemsValidations([
            new AssetFileSizeValidation(null, 10485760),
            new LinkMimetypeGroupValidation(['image']),
        ]);
        $contentType->addField($field);
        $field = new BooleanField('booleanField', 'Boolean Field');
        $contentType->addField($field);
        $field = new DateField('dateField', 'Date Field');
        $contentType->addField($field);
        $field = new IntegerField('integerField', 'Integer Field');
        $contentType->addField($field);
        $field = new LinkField('linkField', 'Link Field', 'Entry');
        $contentType->addField($field);
        $field = new LocationField('locationField', 'Location Field');
        $contentType->addField($field);
        $field = new NumberField('numberField', 'Number Field');
        $contentType->addField($field);
        $field = new ObjectField('objectField', 'Object Field');
        $contentType->addField($field);
        $field = new SymbolField('symbolField', 'Symbol Field');
        $contentType->addField($field);
        $field = new TextField('textField', 'Text Field');
        $contentType->addField($field);

        $client->contentType->create($contentType, 'fullContentType');

        $this->assertNotNull($contentType->getId());

        $contentType = $client->contentType->get('fullContentType');

        $field = $contentType->getField('arrayField');
        $this->assertInstanceOf(ArrayField::class, $field);
        $this->assertEquals('Array', $field->getType());
        $this->assertEquals('Array Field', $field->getName());
        $this->assertEquals('Link', $field->getItemsType());
        $this->assertEquals('Asset', $field->getItemsLinkType());
        $this->assertEquals([
            new AssetFileSizeValidation(null, 10485760),
            new LinkMimetypeGroupValidation(['image']),
        ], $field->getItemsValidations());

        $field = $contentType->getField('booleanField');
        $this->assertInstanceOf(BooleanField::class, $field);
        $this->assertEquals('Boolean', $field->getType());
        $this->assertEquals('Boolean Field', $field->getName());

        $field = $contentType->getField('dateField');
        $this->assertInstanceOf(DateField::class, $field);
        $this->assertEquals('Date', $field->getType());
        $this->assertEquals('Date Field', $field->getName());

        $field = $contentType->getField('integerField');
        $this->assertInstanceOf(IntegerField::class, $field);
        $this->assertEquals('Integer', $field->getType());
        $this->assertEquals('Integer Field', $field->getName());

        $field = $contentType->getField('linkField');
        $this->assertInstanceOf(LinkField::class, $field);
        $this->assertEquals('Link', $field->getType());
        $this->assertEquals('Link Field', $field->getName());
        $this->assertEquals('Entry', $field->getLinkType());

        $field = $contentType->getField('locationField');
        $this->assertInstanceOf(LocationField::class, $field);
        $this->assertEquals('Location', $field->getType());
        $this->assertEquals('Location Field', $field->getName());

        $field = $contentType->getField('numberField');
        $this->assertInstanceOf(NumberField::class, $field);
        $this->assertEquals('Number', $field->getType());
        $this->assertEquals('Number Field', $field->getName());

        $field = $contentType->getField('objectField');
        $this->assertInstanceOf(ObjectField::class, $field);
        $this->assertEquals('Object', $field->getType());
        $this->assertEquals('Object Field', $field->getName());

        $field = $contentType->getField('symbolField');
        $this->assertInstanceOf(SymbolField::class, $field);
        $this->assertEquals('Symbol', $field->getType());
        $this->assertEquals('Symbol Field', $field->getName());

        $field = $contentType->getField('textField');
        $this->assertInstanceOf(TextField::class, $field);
        $this->assertEquals('Text', $field->getType());
        $this->assertEquals('Text Field', $field->getName());

        $client->contentType->delete($contentType);
    }
}
