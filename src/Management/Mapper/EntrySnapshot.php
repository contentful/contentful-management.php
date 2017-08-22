<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Mapper;

use Contentful\Management\Resource\EntrySnapshot as ResourceClass;
use Contentful\Management\SystemProperties;

/**
 * EntrySnapshot class.
 */
class EntrySnapshot extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        if ($resource !== null) {
            throw new \LogicException(sprintf(
                'Trying to update resource %s, which only supports creation',
                static::class
            ));
        }

        return $this->hydrate(ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'entry' => $this->builder->build($data['snapshot']),
        ]);
    }
}
