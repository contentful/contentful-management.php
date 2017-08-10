<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource;

use Contentful\Link;
use Contentful\Management\SystemProperties;

/**
 * BaseResource class.
 */
abstract class BaseResource implements ResourceInterface
{
    /**
     * @var SystemProperties
     */
    protected $sys;

    /**
     * BaseResource constructor.
     *
     * @param string $type The system type
     * @param array  $sys
     */
    protected function __construct(string $type, array $sys = [])
    {
        $sys['type'] = $type;
        $this->sys = new SystemProperties($sys);
    }

    /**
     * {@inheritdoc}
     */
    public function getSystemProperties(): SystemProperties
    {
        return $this->sys;
    }

    /**
     * Creates a Link representation of the current resource.
     *
     * @return Link
     */
    public function asLink(): Link
    {
        return new Link($this->sys->getId(), $this->sys->getType());
    }
}
