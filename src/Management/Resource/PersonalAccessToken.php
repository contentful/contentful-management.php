<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource;

use Contentful\Management\Resource\Behavior\Creatable;
use function Contentful\format_date_for_json;

/**
 * PersonalAccessToken class.
 *
 * This class represents a resource with type "PersonalAccessToken" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/personal-access-tokens
 */
class PersonalAccessToken extends BaseResource implements Creatable
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
            'token' => $this->token,
            'revokedAt' => $this->revokedAt ? format_date_for_json($this->revokedAt) : null,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function asRequestBody(): string
    {
        $body = $this->jsonSerialize();

        unset($body['sys']);
        unset($body['token']);
        unset($body['revokedAt']);

        return json_encode((object) $body, JSON_UNESCAPED_UNICODE);
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
}
