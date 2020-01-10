<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties;

use Contentful\Core\Resource\SystemPropertiesInterface;

/**
 * A SystemProperties instance contains the metadata of a resource.
 */
abstract class BaseSystemProperties implements SystemPropertiesInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $type;

    protected function init(string $type, string $id)
    {
        $this->type = $type;
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
        ];
    }
}
