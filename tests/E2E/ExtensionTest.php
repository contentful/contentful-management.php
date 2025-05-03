<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Management\Resource\Extension;
use Contentful\Management\Resource\Extension\FieldType;
use Contentful\Management\Resource\Extension\Parameter;
use Contentful\Tests\Management\BaseTestCase;

class ExtensionTest extends BaseTestCase
{
    /**
     * @vcr e2e_extension_get_one.json
     */
    public function testGet()
    {
        $proxy = $this->getReadOnlyEnvironmentProxy();

        $extension = $proxy->getExtension('3GKNbc6ddeIYgmWuUc0ami');

        $this->assertSame('Test extension', $extension->getName());
        $this->assertSame('https://www.example.com/cf-test-extension', $extension->getSource());
        $this->assertTrue($extension->isSidebar());
        $this->assertSame(['type' => 'Integer'], $extension->getFieldTypes()[0]->getData());
    }

    /**
     * @vcr e2e_extension_get_collection.json
     */
    public function testGetCollection()
    {
        $proxy = $this->getReadOnlyEnvironmentProxy();

        $extensions = $proxy->getExtensions();

        $this->assertCount(1, $extensions);
        $extension = $extensions[0];

        $this->assertSame('Test extension', $extension->getName());
        $this->assertSame('https://www.example.com/cf-test-extension', $extension->getSource());
        $this->assertTrue($extension->isSidebar());
        $this->assertSame(['type' => 'Integer'], $extension->getFieldTypes()[0]->getData());
    }

    /**
     * @vcr e2e_extension_get_one_from_space_proxy.json
     */
    public function testGetFromSpaceProxy()
    {
        $proxy = $this->getReadOnlySpaceProxy();

        $extension = $proxy->getExtension('master', '3GKNbc6ddeIYgmWuUc0ami');

        $this->assertSame('Test extension', $extension->getName());
        $this->assertSame('https://www.example.com/cf-test-extension', $extension->getSource());
        $this->assertTrue($extension->isSidebar());
        $this->assertSame(['type' => 'Integer'], $extension->getFieldTypes()[0]->getData());
    }

    /**
     * @vcr e2e_extension_get_collection_from_space_proxy.json
     */
    public function testGetCollectionFromSpaceProxy()
    {
        $proxy = $this->getReadOnlySpaceProxy();

        $extensions = $proxy->getExtensions('master');

        $this->assertCount(1, $extensions);
        $extension = $extensions[0];

        $this->assertSame('Test extension', $extension->getName());
        $this->assertSame('https://www.example.com/cf-test-extension', $extension->getSource());
        $this->assertTrue($extension->isSidebar());
        $this->assertSame(['type' => 'Integer'], $extension->getFieldTypes()[0]->getData());
    }

    /**
     * @vcr e2e_extension_create_update_delete.json
     */
    public function testCreateUpdateDelete()
    {
        $proxy = $this->getReadWriteEnvironmentProxy();
        $extension = new Extension('My awesome extension');

        $source = '<!doctype html><html lang="en"><head><meta charset="UTF-8"/><title>Sample Editor Extension</title><link rel="stylesheet" href="https://contentful.github.io/ui-extensions-sdk/cf-extension.css"><script src="https://contentful.github.io/ui-extensions-sdk/cf-extension-api.js"></script></head><body><div id="content"></div><script>window.contentfulExtension.init(function (extension) {window.alert(extension);var value = extension.field.getValue();extension.field.setValue("Hello world!"");extension.field.onValueChanged(function(value) {if (value !== currentValue) {extension.field.setValue("Hello world!"");}});});</script></body></html>';

        $extension
            ->addNewFieldType('Symbol')
            ->addNewFieldType('Array', ['Symbol'])
            ->addNewFieldType('Link', ['Entry'])
            ->setSource($source)
            ->setSidebar(false)
            ->setInstallationParameters([
                new Parameter('name', 'Name', 'Symbol'),
            ])
            ->setInstanceParameters([
                new Parameter('age', 'Age', 'Number'),
            ])
        ;

        $proxy->create($extension);

        $this->assertNotNull($extension->getId());
        $this->assertSame('My awesome extension', $extension->getName());
        $this->assertSame($source, $extension->getSource());
        $this->assertFalse($extension->isSidebar());
        $fieldTypes = $extension->getFieldTypes();
        $this->assertContainsOnlyInstancesOf(FieldType::class, $fieldTypes);
        $this->assertCount(3, $fieldTypes);
        $this->assertSame(['type' => 'Symbol'], $fieldTypes[0]->getData());
        $this->assertSame(['type' => 'Array', 'items' => ['type' => 'Symbol']], $fieldTypes[1]->getData());
        $this->assertSame(['type' => 'Link', 'linkType' => 'Entry'], $fieldTypes[2]->getData());

        $parameters = $extension->getInstallationParameters();
        $this->assertCount(1, $parameters);
        $this->assertContainsOnlyInstancesOf(Parameter::class, $parameters);
        $parameter = $parameters[0];
        $this->assertSame('name', $parameter->getId());
        $this->assertSame('Name', $parameter->getName());
        $this->assertSame('Symbol', $parameter->getType());

        $parameters = $extension->getInstanceParameters();
        $this->assertCount(1, $parameters);
        $this->assertContainsOnlyInstancesOf(Parameter::class, $parameters);
        $parameter = $parameters[0];
        $this->assertSame('age', $parameter->getId());
        $this->assertSame('Age', $parameter->getName());
        $this->assertSame('Number', $parameter->getType());

        $extension->setName('Maybe not-so-awesome extension');
        $extension->setSource('https://www.example.com/cf-ui-extension');
        $extension->setFieldTypes([
            new FieldType('Array', ['Link', 'Asset']),
        ]);
        $extension->setInstanceParameters([]);
        $extension->setInstallationParameters([]);
        $extension->update();

        $this->assertSame('Maybe not-so-awesome extension', $extension->getName());
        $this->assertSame('https://www.example.com/cf-ui-extension', $extension->getSource());
        $this->assertSame([
            'type' => 'Array',
            'items' => [
                'type' => 'Link',
                'linkType' => 'Asset',
            ],
        ], $extension->getFieldTypes()[0]->getData());

        $extension->delete();
    }
}
