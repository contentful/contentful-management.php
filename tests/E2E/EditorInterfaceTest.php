<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\E2E;

use Contentful\Tests\End2EndTestCase;

class EditorInterfaceTest extends End2EndTestCase
{
    /**
     * @vcr e2e_editor_interface_get_update.json
     */
    public function testGetUpdate()
    {
        $client = $this->getReadWriteClient();

        $editorInterface = $client->editorInterface->get('bookmark');

        $control = $editorInterface->getControl('name');
        $this->assertEquals('name', $control->getFieldId());
        $this->assertEquals('singleLine', $control->getWidgetId());
        $this->assertEquals([], $control->getSettings());

        $control = $editorInterface->getControl('website');
        $this->assertEquals('website', $control->getFieldId());
        $this->assertEquals('singleLine', $control->getWidgetId());
        $this->assertEquals([], $control->getSettings());
        $control->setWidgetId('urlEditor');

        $control = $editorInterface->getControl('rating');
        $this->assertEquals('rating', $control->getFieldId());
        $this->assertEquals('numberEditor', $control->getWidgetId());
        $this->assertEquals([], $control->getSettings());
        $control->setWidgetId('rating');
        $control->setSettings(['stars' => 5]);

        try {
            $control = $editorInterface->getControl('invalidControl');
        } catch (\Exception $e) {
            $this->assertInstanceOf(\InvalidArgumentException::class, $e);
            $this->assertEquals('Trying to access unavailable control "invalidControl".', $e->getMessage());
        }

        $editorInterface->update();

        $controls = $editorInterface->getControls();

        $control = $controls[0];
        $this->assertEquals('name', $control->getFieldId());
        $this->assertEquals('singleLine', $control->getWidgetId());
        $this->assertEquals([], $control->getSettings());

        $control = $controls[1];
        $this->assertEquals('website', $control->getFieldId());
        $this->assertEquals('urlEditor', $control->getWidgetId());
        $this->assertEquals([], $control->getSettings());
        $control->setWidgetId('singleLine');

        $control = $controls[2];
        $this->assertEquals('rating', $control->getFieldId());
        $this->assertEquals('rating', $control->getWidgetId());
        $this->assertEquals(['stars' => 5], $control->getSettings());
        $control->setWidgetId('numberEditor');
        $control->setSettings([]);

        $editorInterface->update();
    }
}
