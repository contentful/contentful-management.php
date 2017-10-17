<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Link;
use Contentful\Management\Resource\Behavior\Creatable;
use Contentful\Management\Resource\Behavior\Deletable;
use Contentful\Management\Resource\Behavior\Updatable;

/**
 * SpaceMembership class.
 *
 * This class represents a resource with type "SpaceMembership" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/space-memberships
 */
class SpaceMembership extends BaseResource implements Creatable, Updatable, Deletable
{
    /**
     * @var bool
     */
    protected $admin = false;

    /**
     * @var string|null
     */
    protected $email;

    /**
     * @var Link[]
     */
    protected $roles = [];

    /**
     * @var Link|null
     */
    protected $user;

    /**
     * SpaceMembership constructor.
     */
    public function __construct()
    {
        parent::__construct('SpaceMembership');
    }

    /**
     * Returns an array to be used by "json_encode" to serialize objects of this class.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $spaceMembership = [
            'sys' => $this->sys,
            'admin' => $this->admin,
            'roles' => $this->roles,
        ];

        if ($this->email) {
            $spaceMembership['email'] = $this->email;
        }

        return $spaceMembership;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->admin;
    }

    /**
     * @param bool $admin
     *
     * @return static
     */
    public function setAdmin(bool $admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return static
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Link[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param Link $role
     *
     * @return static
     */
    public function addRole(Link $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Shortcut for adding a link to a role.
     *
     * @param string $roleId
     *
     * @return static
     */
    public function addRoleLink(string $roleId)
    {
        return $this->addRole(new Link($roleId, 'Role'));
    }

    /**
     * @param Link[] $roles
     *
     * @return static
     */
    public function setRoles(array $roles = [])
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Link|null
     */
    public function getUser()
    {
        return $this->user;
    }
}
