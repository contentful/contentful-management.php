<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Resource;

/**
 * User class.
 *
 * This class represents a resource with type "User" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/users/user
 */
class User extends BaseResource
{
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
    final public function __construct()
    {
        throw new \LogicException(\sprintf(
            'Class "%s" can only be instantiated as a result of an API call, manual creation is not allowed.',
            static::class
        ));
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
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'avatarUrl' => $this->avatarUrl,
            'email' => $this->email,
            'activated' => $this->activated,
            'signInCount' => $this->signInCount,
            'confirmed' => $this->confirmed,
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
}
