<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Core\Resource\ResourceInterface;
use Contentful\Management\SystemProperties\Snapshot as SystemProperties;

/**
 * Snapshot class.
 */
abstract class Snapshot extends BaseResource
{
    /**
     * @var SystemProperties
     */
    protected $sys;

    /**
     * @var ResourceInterface
     */
    protected $snapshot;

    /**
     * Snapshot constructor.
     */
    private function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getSystemProperties(): SystemProperties
    {
        return $this->sys;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return [
            'sys' => $this->sys,
            'snapshot' => $this->snapshot,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function asRequestBody()
    {
        throw new \LogicException(\sprintf(
            'Trying to convert object of class "%s" to a request body format, but operation is not supported on this class.',
            static::class
        ));
    }

    /**
     * @return ResourceInterface
     */
    public function getSnapshot(): ResourceInterface
    {
        return $this->snapshot;
    }
}
