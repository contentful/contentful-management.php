<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Core\Api\Link;
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
    public function testGetOne()
    {
        $proxy = $this->getDefaultEnvironmentProxy();

        $contentType = $proxy->getContentType('cat');

        $sys = $contentType->getSystemProperties();
        $this->assertSame('cat', $sys->getId());
        $this->assertSame('ContentType', $sys->getType());
        $this->assertSame(3, $sys->getVersion());
        $this->assertLink($this->defaultSpaceId, 'Space', $sys->getSpace());
        $this->assertSame('2017-10-17T12:23:16.461Z', (string) $sys->getCreatedAt());
        $this->assertSame('2017-10-17T12:23:46.365Z', (string) $sys->getUpdatedAt());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getCreatedBy());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getUpdatedBy());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getPublishedBy());
        $this->assertSame(2, $sys->getPublishedVersion());
        $this->assertSame(1, $sys->getPublishedCounter());
        $this->assertSame('2017-10-17T12:23:46.365Z', (string) $sys->getPublishedAt());
        $this->assertSame('2017-10-17T12:23:46.365Z', (string) $sys->getFirstPublishedAt());

        $this->assertSame('Cat', $contentType->getName());
        $this->assertSame('Meow.', $contentType->getDescription());
        $this->assertLink('cat', 'ContentType', $contentType->asLink());
        $this->assertFalse($contentType->isPublished());

        $fields = $contentType->getFields();
        $this->assertCount(8, $fields);

        $field0 = $fields[0];
        $this->assertInstanceof(TextField::class, $field0);
        $this->assertSame('name', $field0->getId());
        $this->assertSame('Name', $field0->getName());
        $this->assertTrue($field0->isRequired());
        $this->assertTrue($field0->isLocalized());
        $this->assertFalse($field0->isDisabled());
        $this->assertFalse($field0->isOmitted());

        $field0validations = $field0->getValidations();
        $this->assertCount(1, $field0validations);

        $field0validation0 = $field0validations[0];
        $this->assertInstanceOf(SizeValidation::class, $field0validation0);
        $this->assertSame(3, $field0validation0->getMin());
    }

    /**
     * @vcr e2e_content_type_get_one_from_space_proxy.json
     */
    public function testGetOneFromSpaceProxy()
    {
        $proxy = $this->getDefaultSpaceProxy();

        $contentType = $proxy->getContentType('master', 'cat');

        $sys = $contentType->getSystemProperties();
        $this->assertSame('cat', $sys->getId());
        $this->assertSame('ContentType', $sys->getType());
        $this->assertSame(3, $sys->getVersion());
        $this->assertLink($this->defaultSpaceId, 'Space', $sys->getSpace());
        $this->assertSame('2017-10-17T12:23:16.461Z', (string) $sys->getCreatedAt());
        $this->assertSame('2017-10-17T12:23:46.365Z', (string) $sys->getUpdatedAt());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getCreatedBy());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getUpdatedBy());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getPublishedBy());
        $this->assertSame(2, $sys->getPublishedVersion());
        $this->assertSame(1, $sys->getPublishedCounter());
        $this->assertSame('2017-10-17T12:23:46.365Z', (string) $sys->getPublishedAt());
        $this->assertSame('2017-10-17T12:23:46.365Z', (string) $sys->getFirstPublishedAt());

        $this->assertSame('Cat', $contentType->getName());
        $this->assertSame('Meow.', $contentType->getDescription());
        $this->assertLink('cat', 'ContentType', $contentType->asLink());
        $this->assertFalse($contentType->isPublished());

        $fields = $contentType->getFields();
        $this->assertCount(8, $fields);

        $field0 = $fields[0];
        $this->assertInstanceof(TextField::class, $field0);
        $this->assertSame('name', $field0->getId());
        $this->assertSame('Name', $field0->getName());
        $this->assertTrue($field0->isRequired());
        $this->assertTrue($field0->isLocalized());
        $this->assertFalse($field0->isDisabled());
        $this->assertFalse($field0->isOmitted());

        $field0validations = $field0->getValidations();
        $this->assertCount(1, $field0validations);

        $field0validation0 = $field0validations[0];
        $this->assertInstanceOf(SizeValidation::class, $field0validation0);
        $this->assertSame(3, $field0validation0->getMin());
    }

    /**
     * @vcr e2e_content_type_get_published_one.json
     */
    public function testGetPublishedOne()
    {
        $proxy = $this->getDefaultEnvironmentProxy();
        $contentType = $proxy->getPublishedContentType('cat');

        $this->assertSame('Cat', $contentType->getName());
        $this->assertSame('name', $contentType->getDisplayField());
        $this->assertLink('cat', 'ContentType', $contentType->asLink());
        $this->assertTrue($contentType->isPublished());
        $this->assertCount(8, $contentType->getFields());

        $sys = $contentType->getSystemProperties();
        $this->assertSame('cat', $sys->getId());
        $this->assertSame('ContentType', $sys->getType());
        $this->assertSame('2017-10-17T12:23:46.365Z', (string) $sys->getCreatedAt());
        $this->assertSame('2017-10-17T12:23:46.365Z', (string) $sys->getUpdatedAt());
        $this->assertLink($this->defaultSpaceId, 'Space', $sys->getSpace());
        $this->assertLink('master', 'Environment', $sys->getEnvironment());
        $this->assertSame(1, $sys->getRevision());
    }

    /**
     * @vcr e2e_content_type_get_published_one_from_space_proxy.json
     */
    public function testGetPublishedOneFromSpaceProxy()
    {
        $proxy = $this->getDefaultSpaceProxy();
        $contentType = $proxy->getPublishedContentType('master', 'cat');

        $this->assertSame('Cat', $contentType->getName());
        $this->assertSame('name', $contentType->getDisplayField());
        $this->assertLink('cat', 'ContentType', $contentType->asLink());
        $this->assertTrue($contentType->isPublished());
        $this->assertCount(8, $contentType->getFields());

        $sys = $contentType->getSystemProperties();
        $this->assertSame('cat', $sys->getId());
        $this->assertSame('ContentType', $sys->getType());
        $this->assertSame('2017-10-17T12:23:46.365Z', (string) $sys->getCreatedAt());
        $this->assertSame('2017-10-17T12:23:46.365Z', (string) $sys->getUpdatedAt());
        $this->assertLink($this->defaultSpaceId, 'Space', $sys->getSpace());
        $this->assertLink('master', 'Environment', $sys->getEnvironment());
        $this->assertSame(1, $sys->getRevision());
    }

    /**
     * @vcr e2e_content_type_get_collection.json
     */
    public function testGetCollection()
    {
        $proxy = $this->getDefaultEnvironmentProxy();
        $contentTypes = $proxy->getContentTypes();

        $this->assertInstanceOf(ContentType::class, $contentTypes[0]);

        $query = (new Query())
            ->setLimit(1);
        $contentTypes = $proxy->getContentTypes($query);
        $this->assertInstanceOf(ContentType::class, $contentTypes[0]);
        $this->assertCount(1, $contentTypes);
    }

    /**
     * @vcr e2e_content_type_get_collection_from_space_proxy.json
     */
    public function testGetCollectionFromSpaceProxy()
    {
        $proxy = $this->getDefaultSpaceProxy();
        $contentTypes = $proxy->getContentTypes('master');

        $this->assertInstanceOf(ContentType::class, $contentTypes[0]);

        $query = (new Query())
            ->setLimit(1);
        $contentTypes = $proxy->getContentTypes('master', $query);
        $this->assertInstanceOf(ContentType::class, $contentTypes[0]);
        $this->assertCount(1, $contentTypes);
    }

    /**
     * @vcr e2e_content_type_get_published_collection.json
     */
    public function testGetPublishedCollection()
    {
        $proxy = $this->getDefaultEnvironmentProxy();
        $contentTypes = $proxy->getPublishedContentTypes();

        $this->assertInstanceOf(ContentType::class, $contentTypes[0]);

        $query = (new Query())
            ->setLimit(1);
        $contentTypes = $proxy->getPublishedContentTypes($query);
        $this->assertInstanceOf(ContentType::class, $contentTypes[0]);
        $this->assertCount(1, $contentTypes);
    }

    /**
     * @vcr e2e_content_type_get_published_collection_from_space_proxy.json
     */
    public function testGetPublishedCollectionFromSpaceProxy()
    {
        $proxy = $this->getDefaultSpaceProxy();
        $contentTypes = $proxy->getPublishedContentTypes('master');

        $this->assertInstanceOf(ContentType::class, $contentTypes[0]);

        $query = (new Query())
            ->setLimit(1);
        $contentTypes = $proxy->getPublishedContentTypes('master', $query);
        $this->assertInstanceOf(ContentType::class, $contentTypes[0]);
        $this->assertCount(1, $contentTypes);
    }

    /**
     * @vcr e2e_content_type_create_update_publish_delete.json
     */
    public function testContentTypeCreateUpdatePublishDelete()
    {
        $proxy = $this->getDefaultEnvironmentProxy();

        $contentType = (new ContentType('Test CT'))
            ->setDescription('THE best content type');

        $proxy->create($contentType);
        $this->assertNotNull($contentType->getId());
        $this->assertTrue($contentType->getSystemProperties()->isDraft());
        $this->assertFalse($contentType->getSystemProperties()->isPublished());
        $this->assertFalse($contentType->getSystemProperties()->isUpdated());

        $contentType->setName('Test CT - Updates');
        $contentType->update();

        $contentType->publish();
        $this->assertSame(1, $contentType->getSystemProperties()->getPublishedCounter());
        $this->assertSame(2, $contentType->getSystemProperties()->getPublishedVersion());
        $this->assertTrue($contentType->getSystemProperties()->isPublished());

        $contentType->unpublish();
        $this->assertNull($contentType->getSystemProperties()->getPublishedVersion());
        $this->assertFalse($contentType->getSystemProperties()->isPublished());

        $contentType->delete();
    }

    /**
     * @vcr e2e_content_type_create_with_id.json
     */
    public function testCreateWithId()
    {
        $proxy = $this->getDefaultEnvironmentProxy();

        $contentType = (new ContentType('Test CT'))
            ->setDescription('This content type will have `myCustomTestCt` as ID');

        $proxy->create($contentType, 'myCustomTestCt');
        $this->assertSame('myCustomTestCt', $contentType->getId());

        $contentType->delete();
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Trying to access invalid field "invalidField" on content type "bookmark".
     * @vcr e2e_content_type_invalid_field_access.json
     */
    public function testInvalidFieldAccess()
    {
        $contentType = $this->getDefaultEnvironmentProxy()->getContentType('bookmark');
        $contentType->getField('invalidField');
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Invalid items type "invalidItemsLink". Valid values are Symbol, Link.
     */
    public function testInvalidItemsLink()
    {
        new ArrayField('arrayField', 'Array Field', 'invalidItemsLink');
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Invalid items link type "invalidItemsLinkType". Valid values are Asset, Entry.
     */
    public function testInvalidItemsLinkType()
    {
        new ArrayField('arrayField', 'Array Field', 'Link', 'invalidItemsLinkType');
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage The validation "Contentful\Management\Resource\ContentType\Validation\RangeValidation" can not be used for fields of type "Link".
     */
    public function testInvalidItemsValidation()
    {
        $field = new ArrayField('arrayField', 'Array Field', 'Link', 'Asset');
        $field->addItemsValidation(new RangeValidation(0, 50));
    }

    /**
     * @expectedException        \InvalidArgumentException
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
    public function testFullFields()
    {
        $proxy = $this->getDefaultEnvironmentProxy();
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
            ->addValidation(new InValidation([1, 2, 3]));
        $contentType->addNewField('object', 'objectField', 'Object Field');
        $contentType->addNewField('symbol', 'symbolField', 'Symbol Field')
            ->addValidation(new UniqueValidation())
            ->addValidation(new RegexpValidation('^such', 'im'));
        $contentType->addNewField('text', 'textField', 'Text Field');

        $proxy->create($contentType, 'fullContentType');
        $this->assertNotNull($contentType->getId());

        $contentType = $proxy->getContentType('fullContentType');

        // Asserts :allthethings:
        $field = $contentType->getField('arrayField');
        $this->assertInstanceOf(ArrayField::class, $field);
        $this->assertSame('Array', $field->getType());
        $this->assertSame('Array Field', $field->getName());
        $this->assertSame('Link', $field->getItemsType());
        $this->assertSame('Asset', $field->getItemsLinkType());
        // Validations
        $this->assertCount(1, $field->getValidations());
        $validation = $field->getValidations()[0];
        $this->assertInstanceOf(SizeValidation::class, $validation);
        $this->assertSame(1, $validation->getMin());
        $this->assertSame(10, $validation->getMax());
        $itemsValidations = $field->getItemsValidations();
        $this->assertCount(3, $itemsValidations);
        $validation = $itemsValidations[0];
        $this->assertInstanceOf(AssetFileSizeValidation::class, $validation);
        $this->assertNull($validation->getMin());
        $this->assertSame(10485760, $validation->getMax());
        $validation = $itemsValidations[1];
        $this->assertInstanceOf(AssetImageDimensionsValidation::class, $validation);
        $this->assertSame(50, $validation->getMinWidth());
        $this->assertSame(1000, $validation->getMaxWidth());
        $this->assertSame(50, $validation->getMinHeight());
        $this->assertSame(1000, $validation->getMaxHeight());
        $validation = $itemsValidations[2];
        $this->assertInstanceOf(LinkMimetypeGroupValidation::class, $validation);
        $this->assertSame(['image'], $validation->getMimeTypeGroups());

        $field = $contentType->getField('booleanField');
        $this->assertInstanceOf(BooleanField::class, $field);
        $this->assertSame('Boolean', $field->getType());
        $this->assertSame('Boolean Field', $field->getName());

        $field = $contentType->getField('dateField');
        $this->assertInstanceOf(DateField::class, $field);
        $this->assertSame('Date', $field->getType());
        $this->assertSame('Date Field', $field->getName());
        // Validations
        $this->assertCount(1, $field->getValidations());
        $validation = $field->getValidations()[0];
        $this->assertInstanceOf(DateRangeValidation::class, $validation);
        $this->assertSame('2010-01-01', $validation->getMin());
        $this->assertSame('2020-12-31', $validation->getMax());

        $field = $contentType->getField('integerField');
        $this->assertInstanceOf(IntegerField::class, $field);
        $this->assertSame('Integer', $field->getType());
        $this->assertSame('Integer Field', $field->getName());
        // Validations
        $this->assertCount(1, $field->getValidations());
        $validation = $field->getValidations()[0];
        $this->assertInstanceOf(RangeValidation::class, $validation);
        $this->assertSame(1, $validation->getMin());
        $this->assertSame(10, $validation->getMax());

        $field = $contentType->getField('linkField');
        $this->assertInstanceOf(LinkField::class, $field);
        $this->assertSame('Link', $field->getType());
        $this->assertSame('Link Field', $field->getName());
        $this->assertSame('Entry', $field->getLinkType());
        // Validations
        $this->assertCount(1, $field->getValidations());
        $validation = $field->getValidations()[0];
        $this->assertInstanceOf(LinkContentTypeValidation::class, $validation);
        $this->assertSame(['bookmark'], $validation->getContentTypes());

        $field = $contentType->getField('locationField');
        $this->assertInstanceOf(LocationField::class, $field);
        $this->assertSame('Location', $field->getType());
        $this->assertSame('Location Field', $field->getName());

        $field = $contentType->getField('numberField');
        $this->assertInstanceOf(NumberField::class, $field);
        $this->assertSame('Number', $field->getType());
        $this->assertSame('Number Field', $field->getName());
        // Validations
        $this->assertCount(1, $field->getValidations());
        $validation = $field->getValidations()[0];
        $this->assertInstanceOf(InValidation::class, $validation);
        $this->assertSame([1, 2, 3], $validation->getValues());

        $field = $contentType->getField('objectField');
        $this->assertInstanceOf(ObjectField::class, $field);
        $this->assertSame('Object', $field->getType());
        $this->assertSame('Object Field', $field->getName());

        $field = $contentType->getField('symbolField');
        $this->assertInstanceOf(SymbolField::class, $field);
        $this->assertSame('Symbol', $field->getType());
        $this->assertSame('Symbol Field', $field->getName());
        // Validations
        $this->assertCount(2, $field->getValidations());
        $validation = $field->getValidations()[0];
        $this->assertInstanceOf(UniqueValidation::class, $validation);
        $validation = $field->getValidations()[1];
        $this->assertInstanceOf(RegexpValidation::class, $validation);
        $this->assertSame('^such', $validation->getPattern());
        $this->assertSame('im', $validation->getFlags());

        $field = $contentType->getField('textField');
        $this->assertInstanceOf(TextField::class, $field);
        $this->assertSame('Text', $field->getType());
        $this->assertSame('Text Field', $field->getName());

        $contentType->delete();
    }
}
