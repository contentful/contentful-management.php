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
use Contentful\Management\Resource\ContentType\Validation\AssetImageDimensionsValidation;
use Contentful\Management\Resource\ContentType\Validation\DateRangeValidation;
use Contentful\Management\Resource\ContentType\Validation\InValidation;
use Contentful\Management\Resource\ContentType\Validation\LinkContentTypeValidation;
use Contentful\Management\Resource\ContentType\Validation\LinkMimetypeGroupValidation;
use Contentful\Management\Resource\ContentType\Validation\RangeValidation;
use Contentful\Management\Resource\ContentType\Validation\RegexpValidation;
use Contentful\Management\Resource\ContentType\Validation\SizeValidation;
use Contentful\Management\Resource\ContentType\Validation\UniqueValidation;
use Contentful\Tests\Management\BaseTestCase;

class ContentTypeTest extends BaseTestCase
{
    /**
     * @vcr e2e_content_type_get_one.json
     */
    public function testGetContentType()
    {
        $client = $this->getDefaultClient();

        $contentType = $client->contentType->get('cat');

        $sys = $contentType->getSystemProperties();
        $this->assertEquals('cat', $sys->getId());
        $this->assertEquals('ContentType', $sys->getType());
        $this->assertEquals(3, $sys->getVersion());
        $this->assertEquals(new Link($this->defaultSpaceId, 'Space'), $sys->getSpace());
        $this->assertEquals(new ApiDateTime('2017-10-17T12:23:16.461'), $sys->getCreatedAt());
        $this->assertEquals(new ApiDateTime('2017-10-17T12:23:46.365'), $sys->getUpdatedAt());
        $this->assertEquals(new Link('1CECdY5ZhqJapGieg6QS9P', 'User'), $sys->getCreatedBy());
        $this->assertEquals(new Link('1CECdY5ZhqJapGieg6QS9P', 'User'), $sys->getUpdatedBy());
        $this->assertEquals(new Link('1CECdY5ZhqJapGieg6QS9P', 'User'), $sys->getPublishedBy());
        $this->assertEquals(2, $sys->getPublishedVersion());
        $this->assertEquals(1, $sys->getPublishedCounter());
        $this->assertEquals(new ApiDateTime('2017-10-17T12:23:46.365'), $sys->getPublishedAt());
        $this->assertEquals(new ApiDateTime('2017-10-17T12:23:46.365'), $sys->getFirstPublishedAt());

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
    }

    /**
     * @vcr e2e_content_type_get_collection.json
     */
    public function testGetContentTypes()
    {
        $client = $this->getDefaultClient();
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
        $client = $this->getDefaultClient();

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
        $client = $this->getDefaultClient();

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
        $contentType = $this->getDefaultClient()->contentType->get('bookmark');
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
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Trying to instantiate invalid field class "invalidField".
     */
    public function testInvalidFieldCreation()
    {
        $contentType = new ContentType('test');
        $contentType->createField('invalidField', 'fieldId', 'Field Name');
    }

    /**
     * @vcr e2e_content_type_full_fields.json
     */
    public function testContentTypeFields()
    {
        $client = $this->getDefaultClient();
        $contentType = new ContentType('fullContentType');
        $contentType->setName('Full Content Type');
        $contentType->setDescription('This content type includes all field types');

        $contentType->addNewField('array', 'arrayField', 'Array Field', 'Link', 'Asset')
            ->addValidation(new SizeValidation(1, 10))
            ->setItemsValidations([
                new AssetFileSizeValidation(null, 10485760),
                new AssetImageDimensionsValidation(50, 1000, 50, 1000),
                new LinkMimetypeGroupValidation(['image']),
            ]);
        $contentType->addNewField('boolean', 'booleanField', 'Boolean Field');
        $contentType->addNewField('date', 'dateField', 'Date Field')
            ->addValidation(new DateRangeValidation('2010-01-01', '2020-12-31'));
        $contentType->addNewField('integer', 'integerField', 'Integer Field')
            ->addValidation(new RangeValidation(1, 10));
        $contentType->addNewField('link', 'linkField', 'Link Field', 'Entry')
            ->addValidation(new LinkContentTypeValidation(['bookmark']));
        $contentType->addNewField('location', 'locationField', 'Location Field');
        $contentType->addNewField('number', 'numberField', 'Number Field')
            ->addValidation(new InValidation([1.0, 2.0, 3.0]));
        $contentType->addNewField('object', 'objectField', 'Object Field');
        $contentType->addNewField('symbol', 'symbolField', 'Symbol Field')
            ->addValidation(new UniqueValidation())
            ->addValidation(new RegexpValidation('^such', 'im'));
        $contentType->addNewField('text', 'textField', 'Text Field');

        $client->contentType->create($contentType, 'fullContentType');
        $this->assertNotNull($contentType->getId());

        $contentType = $client->contentType->get('fullContentType');

        // Asserts :allthethings:
        $field = $contentType->getField('arrayField');
        $this->assertInstanceOf(ArrayField::class, $field);
        $this->assertEquals('Array', $field->getType());
        $this->assertEquals('Array Field', $field->getName());
        $this->assertEquals('Link', $field->getItemsType());
        $this->assertEquals('Asset', $field->getItemsLinkType());
        $this->assertEquals([new SizeValidation(1, 10)], $field->getValidations());
        $this->assertEquals([
            new AssetFileSizeValidation(null, 10485760),
            new AssetImageDimensionsValidation(50, 1000, 50, 1000),
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
        $this->assertEquals([new DateRangeValidation('2010-01-01', '2020-12-31')], $field->getValidations());

        $field = $contentType->getField('integerField');
        $this->assertInstanceOf(IntegerField::class, $field);
        $this->assertEquals('Integer', $field->getType());
        $this->assertEquals('Integer Field', $field->getName());
        $this->assertEquals([new RangeValidation(1, 10)], $field->getValidations());

        $field = $contentType->getField('linkField');
        $this->assertInstanceOf(LinkField::class, $field);
        $this->assertEquals('Link', $field->getType());
        $this->assertEquals('Link Field', $field->getName());
        $this->assertEquals('Entry', $field->getLinkType());
        $this->assertEquals([new LinkContentTypeValidation(['bookmark'])], $field->getValidations());

        $field = $contentType->getField('locationField');
        $this->assertInstanceOf(LocationField::class, $field);
        $this->assertEquals('Location', $field->getType());
        $this->assertEquals('Location Field', $field->getName());

        $field = $contentType->getField('numberField');
        $this->assertInstanceOf(NumberField::class, $field);
        $this->assertEquals('Number', $field->getType());
        $this->assertEquals('Number Field', $field->getName());
        $this->assertEquals([new InValidation([1.0, 2.0, 3.0])], $field->getValidations());

        $field = $contentType->getField('objectField');
        $this->assertInstanceOf(ObjectField::class, $field);
        $this->assertEquals('Object', $field->getType());
        $this->assertEquals('Object Field', $field->getName());

        $field = $contentType->getField('symbolField');
        $this->assertInstanceOf(SymbolField::class, $field);
        $this->assertEquals('Symbol', $field->getType());
        $this->assertEquals('Symbol Field', $field->getName());
        $this->assertEquals([
            new UniqueValidation(),
            new RegexpValidation('^such', 'im'),
        ], $field->getValidations());

        $field = $contentType->getField('textField');
        $this->assertInstanceOf(TextField::class, $field);
        $this->assertEquals('Text', $field->getType());
        $this->assertEquals('Text Field', $field->getName());

        $client->contentType->delete($contentType);
    }
}
