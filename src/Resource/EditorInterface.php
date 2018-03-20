<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Management\Resource\Behavior\UpdatableTrait;
use Contentful\Management\Resource\EditorInterface\Control;
use function GuzzleHttp\json_encode;

/**
 * EditorInterface class.
 *
 * This class represents a resource with type "EditorInterface" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/editor-interface
 */
class EditorInterface extends BaseResource
{
    use UpdatableTrait;

    /**
     * @var Control[]
     */
    protected $controls = [];

    /**
     * EditorInterface constructor.
     */
    final public function __construct()
    {
        throw new \LogicException(\sprintf(
            'Class "%s" can only be instantiated as a result of an API call, manual creation is not allowed.',
            static::class
        ));
    }

    /**
     * Returns an array to be used by "json_encode" to serialize objects of this class.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'sys' => $this->sys,
            'controls' => $this->controls,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function asUriParameters(): array
    {
        return [
            'space' => $this->sys->getSpace()->getId(),
            'environment' => $this->sys->getEnvironment()->getId(),
            'contentType' => $this->sys->getContentType()->getId(),
        ];
    }

    /**
     * @param string $fieldId
     *
     * @return Control
     */
    public function getControl(string $fieldId): Control
    {
        foreach ($this->controls as $control) {
            if ($control->getFieldId() === $fieldId) {
                return $control;
            }
        }

        throw new \InvalidArgumentException(\sprintf(
            'Trying to access unavailable control "%s".',
            $fieldId
        ));
    }

    /**
     * @return Control[]
     */
    public function getControls(): array
    {
        return $this->controls;
    }
}
