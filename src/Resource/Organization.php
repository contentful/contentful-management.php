<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Management\SystemProperties\Organization as SystemProperties;

/**
 * Organization class.
 *
 * This class represents a resource with type "Organization" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/organizations
 * @see https://www.contentful.com/r/knowledgebase/spaces-and-organizations/
 */
class Organization extends BaseResource
{
    /**
     * @var SystemProperties
     */
    protected $sys;

    /**
     * @var string
     */
    protected $name = '';

    /**
     * Organization constructor.
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
            'name' => $this->name,
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
     * {@inheritdoc}
     */
    public function asUriParameters(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
