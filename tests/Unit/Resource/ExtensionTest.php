<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Management\Resource;

use Contentful\Management\Resource\Extension;
use Contentful\Management\Resource\Extension\FieldType;
use PHPUnit\Framework\TestCase;
use function GuzzleHttp\json_encode;

class ExtensionTest extends TestCase
{
    public function testGetSetData()
    {
        $extension = new Extension('Test');
        $this->assertEquals('Test', $extension->getName());

        $extension->setSidebar(false);
        $this->assertFalse($extension->isSidebar());

        $extension->setFieldTypes([new FieldType('Symbol')]);
        $this->assertEquals([new FieldType('Symbol')], $extension->getFieldTypes());

        $extension->setSource('https://www.example.com/cf-ui-extension-test');
        $this->assertEquals('https://www.example.com/cf-ui-extension-test', $extension->getSource());
    }

    public function testJsonSerialize()
    {
        $extension = (new Extension(''))
            ->setName('My extension')
            ->addFieldType(new FieldType('Symbol'))
            ->setSidebar(true)
            ->setSource('https://www.example.com/cf-ui-extension-test');

        $json = '{"sys":{"type":"Extension"},"extension":{"name":"My extension","fieldTypes":[{"type":"Symbol"}],"src":"https:\/\/www.example.com\/cf-ui-extension-test","sidebar":true}}';

        $this->assertJsonStringEqualsJsonString($json, json_encode($extension));
    }
}
