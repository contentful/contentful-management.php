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
     * @expectedException \LogicException
     * @expectedExceptionMessage Class "Contentful\Management\Resource\EditorInterface" can only be instantiated as a result of an API call, manual creation is not allowed.
     */
    public function testInvalidCreation()
    {
        new EditorInterface();
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
