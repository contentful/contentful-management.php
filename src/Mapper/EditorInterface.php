<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Mapper;

use Contentful\Management\Resource\EditorInterface as ResourceClass;
use Contentful\Management\Resource\EditorInterface\Control;
use Contentful\Management\SystemProperties\EditorInterface as SystemProperties;

/**
 * DeliveryApiKey class.
 *
 * This class is responsible for converting raw API data into a PHP object
 * of class Contentful\Management\Resource\EditorInterface.
 */
class EditorInterface extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        /** @var ResourceClass $editorInterface */
        $editorInterface = $this->hydrator->hydrate($resource ?: ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'controls' => \array_map([$this, 'mapControl'], $data['controls']),
        ]);

        return $editorInterface;
    }

    /**
     * @param array $data
     *
     * @return Control
     */
    protected function mapControl(array $data): Control
    {
        /** @var Control $control */
        $control = $this->hydrator->hydrate(Control::class, [
            'fieldId' => $data['fieldId'],
            'widgetId' => $data['widgetId'],
            'settings' => $data['settings'] ?? [],
        ]);

        return $control;
    }
}
