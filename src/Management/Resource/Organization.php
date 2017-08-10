<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource;

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
     * @var string
     */
    protected $name = '';

    /**
     * Organization constructor.
     */
    final public function __construct()
    {
        throw new \LogicException(sprintf('Class %s can only be instantiated as a result of an API call, manual creation is not allowed.', static::class));
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
