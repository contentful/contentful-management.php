<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource;

/**
 * Space class.
 *
 * This class represents a resource with type "Space" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/spaces
 * @see https://www.contentful.com/r/knowledgebase/spaces-and-organizations/
 */
class Space extends BaseResource
{
    /**
     * @var string
     */
    protected $name;

    /**
     * Space constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct('Space');
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return static
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Returns an array to be used by "json_encode" to serialize objects of this class.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'sys' => $this->sys,
            'name' => $this->name,
        ];
    }
}
