<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Management\Resource\Extension;
use Contentful\Management\Resource\Extension\FieldType;
use Contentful\Tests\Management\BaseTestCase;

class ExtensionTest extends BaseTestCase
{
    /**
     * @vcr e2e_extension_get.json
     */
    public function testGet()
    {
        $client = $this->getDefaultClient();

        $extension = $client->extension->get('3GKNbc6ddeIYgmWuUc0ami');

        $this->assertSame('Test extension', $extension->getName());
        $this->assertSame('https://www.example.com/cf-test-extension', $extension->getSource());
        $this->assertTrue($extension->isSidebar());
        $this->assertSame(['type' => 'Integer'], $extension->getFieldTypes()[0]->getData());

        $extensions = $client->extension->getAll();

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
        $client = $this->getDefaultClient();
        $extension = new Extension('My awesome extension');

        $source = '<!doctype html><html lang="en"><head><meta charset="UTF-8"/><title>Sample Editor Extension</title><link rel="stylesheet" href="https://contentful.github.io/ui-extensions-sdk/cf-extension.css"><script src="https://contentful.github.io/ui-extensions-sdk/cf-extension-api.js"></script></head><body><div id="content"></div><script>window.contentfulExtension.init(function (extension) {window.alert(extension);var value = extension.field.getValue();extension.field.setValue("Hello world!"");extension.field.onValueChanged(function(value) {if (value !== currentValue) {extension.field.setValue("Hello world!"");}});});</script></body></html>';

        $extension
            ->addNewFieldType('Symbol')
            ->addNewFieldType('Array', ['Symbol'])
            ->addNewFieldType('Link', ['Entry'])
            ->setSource($source)
            ->setSidebar(false);

        $client->extension->create($extension);

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

        $extension->setName('Maybe not-so-awesome extension');
        $extension->setSource('https://www.example.com/cf-ui-extension');
        $extension->setFieldTypes([
            new FieldType('Array', ['Link', 'Asset']),
        ]);
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
