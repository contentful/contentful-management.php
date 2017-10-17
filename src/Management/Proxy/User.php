<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Proxy;

use Contentful\Management\Resource\User as ResourceClass;

/**
 * User class.
 *
 * This class is used as a proxy for doing operations related to users.
 */
class User extends BaseProxy
{
    /**
     * {@inheritdoc}
     */
    protected $requiresSpaceId = false;

    /**
     * {@inheritdoc}
     */
    public function getResourceUri(array $values): string
    {
        return \rtrim(\strtr('users/{resourceId}', $values), '/');
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabledMethods(): array
    {
        return [];
    }

    /**
     * Returns a User object which corresponds to the current user in Contentful.
     *
     * @return User
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/users/user
     */
    public function getMe(): ResourceClass
    {
        return $this->getResource([
            '{resourceId}' => 'me',
        ]);
    }
}
