<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource;

use Contentful\Management\Resource\Extension;
use Contentful\Management\Resource\Extension\FieldType;
use Contentful\Management\Resource\Extension\Parameter;
use Contentful\Tests\Management\BaseTestCase;

class ExtensionTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $extension = new Extension('Test');
        $this->assertSame('Test', $extension->getName());

        $extension->setSidebar(false);
        $this->assertFalse($extension->isSidebar());

        $fieldTypes = [new FieldType('Symbol')];
        $extension->setFieldTypes($fieldTypes);
        $this->assertSame($fieldTypes, $extension->getFieldTypes());

        $extension->setSource('https://www.example.com/cf-ui-extension-test');
        $this->assertSame('https://www.example.com/cf-ui-extension-test', $extension->getSource());

        $this->assertSame([], $extension->getInstallationParameters());
        $parameter = new Parameter('id', 'Name', 'Symbol');
        $extension->addInstallationParameter($parameter);
        $this->assertSame([$parameter], $extension->getInstallationParameters());
        $extension->setInstallationParameters([]);
        $this->assertSame([], $extension->getInstallationParameters());
        $extension->setInstallationParameters([$parameter]);
        $this->assertSame([$parameter], $extension->getInstallationParameters());

        $this->assertSame([], $extension->getInstanceParameters());
        $parameter = new Parameter('id', 'Name', 'Symbol');
        $extension->addInstanceParameter($parameter);
        $this->assertSame([$parameter], $extension->getInstanceParameters());
        $extension->setInstanceParameters([]);
        $this->assertSame([], $extension->getInstanceParameters());
        $extension->setInstanceParameters([$parameter]);
        $this->assertSame([$parameter], $extension->getInstanceParameters());
    }

    public function testJsonSerialize()
    {
        $extension = (new Extension(''))
            ->setName('My extension')
            ->addFieldType(new FieldType('Symbol'))
            ->setSidebar(true)
            ->setSource('https://www.example.com/cf-ui-extension-test')
            ->addInstallationParameter(new Parameter('id', 'Name', 'Symbol'))
            ->addInstanceParameter(new Parameter('anotherId', 'Another name', 'Number'))
        ;

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/extension.json', $extension);
    }
}
