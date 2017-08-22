<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

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
     * @return $this
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
     * @return $this
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * Returns an array to be used by `json_encode` to serialize objects of this class.
     *
     * @return array
     *
     * @see http://php.net/manual/en/jsonserializable.jsonserialize.php JsonSerializable::jsonSerialize
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
