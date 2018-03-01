<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Management\Resource\Behavior\Creatable;
use Contentful\Management\Resource\Behavior\Deletable;
use Contentful\Management\Resource\Behavior\Updatable;
use Contentful\Management\Resource\Role\Permissions;
use Contentful\Management\Resource\Role\Policy;

/**
 * Role class.
 *
 * This class represents a resource with type "Role" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/roles
 */
class Role extends BaseResource implements Creatable, Updatable, Deletable
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var Policy[]
     */
    protected $policies;

    /**
     * @var Permissions
     */
    protected $permissions;

    /**
     * Role constructor.
     *
     * @param string $name
     * @param string $description
     */
    public function __construct(string $name = '', string $description = '')
    {
        parent::__construct('Role');
        $this->name = $name;
        $this->description = $description;
        $this->permissions = new Permissions();
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
            'description' => $this->description,
            'permissions' => $this->permissions,
            'policies' => $this->policies,
        ];
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
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return static
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Permissions
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @return Policy[]
     */
    public function getPolicies(): array
    {
        return $this->policies;
    }

    /**
     * @param Policy[] $policies
     *
     * @return static
     */
    public function setPolicies(array $policies)
    {
        $this->policies = $policies;

        return $this;
    }

    /**
     * @param Policy $policy
     *
     * @return static
     */
    public function addPolicy(Policy $policy)
    {
        $this->policies[] = $policy;

        return $this;
    }
}
