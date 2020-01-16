<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\EditorInterface;

/**
 * Control class.
 */
class Control implements \JsonSerializable
{
    /**
     * @var string
     */
    protected $fieldId = '';

    /**
     * @var string
     */
    protected $widgetId = '';

    /**
     * @var array
     */
    protected $settings = [];

    public function getFieldId(): string
    {
        return $this->fieldId;
    }

    public function getWidgetId(): string
    {
        return $this->widgetId;
    }

    /**
     * @return static
     */
    public function setWidgetId(string $widgetId)
    {
        $this->widgetId = $widgetId;

        return $this;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * @return static
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        $control = [
            'fieldId' => $this->fieldId,
            'widgetId' => $this->widgetId,
        ];

        if ($this->settings) {
            $control['settings'] = $this->settings;
        }

        return $control;
    }
}
