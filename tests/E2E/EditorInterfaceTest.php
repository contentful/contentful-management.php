<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Tests\Management\BaseTestCase;

class EditorInterfaceTest extends BaseTestCase
{
    /**
     * @vcr e2e_editor_interface_get_update.json
     */
    public function testGetUpdate()
    {
        $editorInterface = $this->getReadWriteEnvironmentProxy()
            ->getEditorInterface('bookmark')
        ;

        $control = $editorInterface->getControl('name');
        $this->assertSame('name', $control->getFieldId());
        $this->assertSame('singleLine', $control->getWidgetId());
        $this->assertSame([], $control->getSettings());

        $control = $editorInterface->getControl('website');
        $this->assertSame('website', $control->getFieldId());
        $this->assertSame('singleLine', $control->getWidgetId());
        $this->assertSame([], $control->getSettings());
        $control->setWidgetId('urlEditor');

        $control = $editorInterface->getControl('rating');
        $this->assertSame('rating', $control->getFieldId());
        $this->assertSame('numberEditor', $control->getWidgetId());
        $this->assertSame([], $control->getSettings());
        $control->setWidgetId('rating');
        $control->setSettings(['stars' => 5]);

        try {
            $editorInterface->getControl('invalidControl');
        } catch (\Exception $exception) {
            $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
            $this->assertSame('Trying to access unavailable control "invalidControl".', $exception->getMessage());
        }

        $editorInterface->update();

        $controls = $editorInterface->getControls();

        $control = $controls[0];
        $this->assertSame('name', $control->getFieldId());
        $this->assertSame('singleLine', $control->getWidgetId());
        $this->assertSame([], $control->getSettings());

        $control = $controls[1];
        $this->assertSame('website', $control->getFieldId());
        $this->assertSame('urlEditor', $control->getWidgetId());
        $this->assertSame([], $control->getSettings());
        $control->setWidgetId('singleLine');

        $control = $controls[2];
        $this->assertSame('rating', $control->getFieldId());
        $this->assertSame('rating', $control->getWidgetId());
        $this->assertSame(['stars' => 5], $control->getSettings());
        $control->setWidgetId('numberEditor');
        $control->setSettings([]);

        $editorInterface->update();
    }

    /**
     * @vcr e2e_editor_interface_get_from_content_type.json
     */
    public function testGetFromContentType()
    {
        $contentType = $this->getReadOnlyEnvironmentProxy()
            ->getContentType('bookmark')
        ;

        $editorInterface = $contentType->getEditorInterface();

        $control = $editorInterface->getControl('name');
        $this->assertSame('name', $control->getFieldId());
        $this->assertSame('singleLine', $control->getWidgetId());
        $this->assertSame([], $control->getSettings());

        $control = $editorInterface->getControl('website');
        $this->assertSame('website', $control->getFieldId());
        $this->assertSame('singleLine', $control->getWidgetId());
        $this->assertSame([], $control->getSettings());
        $control->setWidgetId('urlEditor');

        $control = $editorInterface->getControl('rating');
        $this->assertSame('rating', $control->getFieldId());
        $this->assertSame('numberEditor', $control->getWidgetId());
        $this->assertSame([], $control->getSettings());
        $control->setWidgetId('rating');
        $control->setSettings(['stars' => 5]);
    }

    /**
     * @vcr e2e_editor_interface_get_from_space_proxy.json
     */
    public function testGetFromSpaceProxy()
    {
        $editorInterface = $this->getReadOnlySpaceProxy()
            ->getEditorInterface('master', 'bookmark')
        ;

        $control = $editorInterface->getControl('name');
        $this->assertSame('name', $control->getFieldId());
        $this->assertSame('singleLine', $control->getWidgetId());
        $this->assertSame([], $control->getSettings());

        $control = $editorInterface->getControl('website');
        $this->assertSame('website', $control->getFieldId());
        $this->assertSame('singleLine', $control->getWidgetId());
        $this->assertSame([], $control->getSettings());
        $control->setWidgetId('urlEditor');

        $control = $editorInterface->getControl('rating');
        $this->assertSame('rating', $control->getFieldId());
        $this->assertSame('numberEditor', $control->getWidgetId());
        $this->assertSame([], $control->getSettings());
        $control->setWidgetId('rating');
        $control->setSettings(['stars' => 5]);
    }
}
