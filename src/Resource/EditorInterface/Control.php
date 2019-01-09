<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
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

    /**
     * @return string
     */
    public function getFieldId(): string
    {
        return $this->fieldId;
    }

    /**
     * @return string
     */
    public function getWidgetId(): string
    {
        return $this->widgetId;
    }

    /**
     * @param string $widgetId
     *
     * @return static
     */
    public function setWidgetId(string $widgetId)
    {
        $this->widgetId = $widgetId;

        return $this;
    }

    /**
     * @return array
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * @param array $settings
     *
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
