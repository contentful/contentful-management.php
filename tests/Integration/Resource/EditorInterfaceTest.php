<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Integration\Resource;

use Contentful\Management\ResourceBuilder;
use Contentful\Management\Resource\EditorInterface;
use PHPUnit\Framework\TestCase;

class EditorInterfaceTest extends TestCase
{
    /**
     * @expectedException \LogicException
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

        $json = '{"sys":{"type":"EditorInterface"},"controls":[{"fieldId":"name","widgetId":"singleLine"},{"fieldId":"url","widgetId": "urlEditor"},{"fieldId":"rating","widgetId":"rating","settings":{"stars":5}}]}';

        $this->assertJsonStringEqualsJsonString($json, json_encode($editorInterface));
    }
}
