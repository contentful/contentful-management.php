<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource;

use Contentful\Management\SystemProperties;

/**
 * Organization class.
 *
 * This class represents a resource with type "Organization" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/organizations
 * @see https://www.contentful.com/r/knowledgebase/spaces-and-organizations/
 */
class Organization implements ResourceInterface
{
    /**
     * @var SystemProperties
     */
    private $sys;

    /**
     * @var string
     */
    private $name = '';

    /**
     * Organization constructor.
     */
    public function __construct()
    {
        $this->sys = SystemProperties::withType('Organization');
    }

    /**
     * {@inheritdoc}
     */
    public function getSystemProperties(): SystemProperties
    {
        return $this->sys;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
        return [
            'sys' => $this->sys,
            'name' => $this->name,
        ];
    }
}
