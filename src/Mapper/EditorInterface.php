<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Mapper;

use Contentful\Management\Resource\EditorInterface as ResourceClass;
use Contentful\Management\Resource\EditorInterface\Control;
use Contentful\Management\SystemProperties;

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
        return $this->hydrator->hydrate($resource ?: ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'controls' => \array_map([$this, 'mapControl'], $data['controls']),
        ]);
    }

    /**
     * @param array $data
     *
     * @return Control
     */
    protected function mapControl(array $data): Control
    {
        return $this->hydrator->hydrate(Control::class, [
            'fieldId' => $data['fieldId'],
            'widgetId' => $data['widgetId'],
            'settings' => $data['settings'] ?? [],
        ]);
    }
}
