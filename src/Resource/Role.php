<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Management\Resource\Behavior\CreatableInterface;
use Contentful\Management\Resource\Behavior\DeletableTrait;
use Contentful\Management\Resource\Behavior\UpdatableTrait;
use Contentful\Management\Resource\Role\Permissions;
use Contentful\Management\Resource\Role\Policy;
use Contentful\Management\SystemProperties\Role as SystemProperties;

/**
 * Role class.
 *
 * This class represents a resource with type "Role" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/roles
 */
class Role extends BaseResource implements CreatableInterface
{
    use DeletableTrait,
        UpdatableTrait;

    /**
     * @var SystemProperties
     */
    protected $sys;

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
        $this->name = $name;
        $this->description = $description;
        $this->permissions = new Permissions();
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
     * {@inheritdoc}
     */
    public function asUriParameters(): array
    {
        return [
            'space' => $this->sys->getSpace()->getId(),
            'role' => $this->sys->getId(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getHeadersForCreation(): array
    {
        return [];
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
