<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\Integration\Resource;

use Contentful\Management\Resource\EditorInterface;
use Contentful\Management\ResourceBuilder;
use Contentful\Tests\Management\BaseTestCase;

class EditorInterfaceTest extends BaseTestCase
{
    /**
     * @expectedException \Error
     */
    public function testInvalidCreation()
    {
        new EditorInterface();
    }

    /**
     * This test is meaningless.
     * It's only here because private, empty constructors are not correctly picked by code coverage.
     */
    public function testConstructor()
    {
        $class = new \ReflectionClass(EditorInterface::class);
        $object = $class->newInstanceWithoutConstructor();
        $constructor = $class->getConstructor();
        $constructor->setAccessible(true);
        $constructor->invoke($object);

        $this->markTestAsPassed();
    }

    public function testJsonSerialize()
    {
        $editorInterface = (new ResourceBuilder())->build([
            'sys' => [
                'type' => 'EditorInterface',
            ],
            'controls' => [
                [
                    'fieldId' => 'name',
                    'widgetId' => 'singleLine',
                ],
                [
                    'fieldId' => 'url',
                    'widgetId' => 'urlEditor',
                ],
                [
                    'fieldId' => 'rating',
                    'widgetId' => 'rating',
                    'settings' => [
                        'stars' => 5,
                    ],
                ],
            ],
        ]);

        $this->assertJsonFixtureEqualsJsonObject('Integration/Resource/editor_interface.json', $editorInterface);
    }
}
