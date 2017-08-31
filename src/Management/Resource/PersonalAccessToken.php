<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource;

/**
 * PersonalAccessToken class.
 *
 * This class represents a resource with type "PersonalAccessToken" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/personal-access-tokens
 */
class PersonalAccessToken extends BaseResource
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var \DateTimeImmutable|null
     */
    protected $revokedAt;

    /**
     * @var bool
     */
    protected $isReadOnly = false;

    /**
     * @var string|null
     */
    protected $token;

    /**
     * PersonalAccessToken constructor.
     *
     * @param string $name
     * @param bool   $isReadOnlyScope
     */
    public function __construct(string $name = '', bool $isReadOnly = false)
    {
        parent::__construct('PersonalAccessToken');
        $this->name = $name;
        $this->isReadOnly = $isReadOnly;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getRevokedAt()
    {
        return $this->revokedAt;
    }

    /**
     * @param bool $isReadOnly
     */
    public function setReadOnly(bool $isReadOnly)
    {
        $this->isReadOnly = $isReadOnly;

        return $this;
    }

    /**
     * @return bool
     */
    public function isReadOnly(): bool
    {
        return $this->isReadOnly;
    }

    /**
     * @return string|null
     */
    public function getToken()
    {
        return $this->token;
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
            'scopes' => $this->isReadOnly
                ? ['content_management_read']
                : ['content_management_manage'],
        ];
    }
}
