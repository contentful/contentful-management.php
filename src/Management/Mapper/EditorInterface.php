<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
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
        return $this->hydrate($resource ?: ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'controls' => \array_map([$this, 'buildControl'], $data['controls']),
        ]);
    }

    /**
     * @param array $data
     *
     * @return Control
     */
    protected function buildControl(array $data): Control
    {
        return $this->hydrate(Control::class, [
            'fieldId' => $data['fieldId'],
            'widgetId' => $data['widgetId'],
            'settings' => $data['settings'] ?? [],
        ]);
    }
}
