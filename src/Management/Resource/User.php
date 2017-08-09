<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource;

use Contentful\Link;
use Contentful\Management\Behavior\Linkable;
use Contentful\Management\SystemProperties;

/**
 * User class.
 *
 * This class represents a resource with type "User" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/users/user
 */
class User implements ResourceInterface, Linkable
{
    /**
     * @var SystemProperties
     */
    protected $sys;

    /**
     * @var string
     */
    protected $firstName = '';

    /**
     * @var string
     */
    protected $lastName = '';

    /**
     * @var string
     */
    protected $avatarUrl = '';

    /**
     * @var string
     */
    protected $email = '';

    /**
     * @var bool
     */
    protected $activated = false;

    /**
     * @var int
     */
    protected $signInCount = 0;

    /**
     * @var bool
     */
    protected $confirmed = false;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->sys = SystemProperties::withType('User');
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
    public function asLink(): Link
    {
        return new Link($this->sys->getId(), 'User');
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getAvatarUrl(): string
    {
        return $this->avatarUrl;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return bool
     */
    public function isActivated(): bool
    {
        return $this->activated;
    }

    /**
     * @return int
     */
    public function getSignInCount(): int
    {
        return $this->signInCount;
    }

    /**
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->confirmed;
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
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'avatarUrl' => $this->avatarUrl,
            'email' => $this->email,
            'activated' => $this->activated,
            'signInCount' => $this->signInCount,
            'confirmed' => $this->confirmed,
        ];
    }
}
