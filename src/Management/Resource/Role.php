<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource;

use Contentful\Management\Behavior\Creatable;
use Contentful\Management\Behavior\Deletable;
use Contentful\Management\Behavior\Updatable;
use Contentful\Management\Role\Permissions;
use Contentful\Management\Role\Policy;

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
     * {@inheritdoc}
     */
    public function getResourceUriPart(): string
    {
        return 'roles';
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
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    public function setPolicies(array $policies)
    {
        $this->policies = $policies;

        return $this;
    }

    /**
     * @param Policy $policy
     *
     * @return $this
     */
    public function addPolicy(Policy $policy)
    {
        $this->policies[] = $policy;

        return $this;
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
            'description' => $this->description,
            'permissions' => $this->permissions,
            'policies' => $this->policies,
        ];
    }
}
