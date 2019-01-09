<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Mapper;

use Contentful\Management\Resource\Role as ResourceClass;
use Contentful\Management\Resource\Role\Constraint\AndConstraint;
use Contentful\Management\Resource\Role\Constraint\ConstraintInterface;
use Contentful\Management\Resource\Role\Constraint\EqualityConstraint;
use Contentful\Management\Resource\Role\Constraint\NotConstraint;
use Contentful\Management\Resource\Role\Constraint\OrConstraint;
use Contentful\Management\Resource\Role\Constraint\PathsConstraint;
use Contentful\Management\Resource\Role\Permissions;
use Contentful\Management\Resource\Role\Policy;
use Contentful\Management\SystemProperties\Role as SystemProperties;

/**
 * Role class.
 *
 * This class is responsible for converting raw API data into a PHP object
 * of class Contentful\Management\Resource\Role.
 */
class Role extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        /** @var ResourceClass $role */
        $role = $this->hydrator->hydrate($resource ?: ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'name' => $data['name'],
            'description' => $data['description'],
            'policies' => \array_map([$this, 'mapPolicy'], $data['policies']),
            'permissions' => $this->mapPermissions($data['permissions']),
        ]);

        return $role;
    }

    /**
     * @param array $data
     *
     * @return Policy
     */
    protected function mapPolicy(array $data): Policy
    {
        return new Policy(
            $data['effect'],
            $data['actions'],
            isset($data['constraint']) ? $this->mapConstraint($data['constraint']) : \null
        );
    }

    /**
     * @param array $data
     *
     * @return ConstraintInterface
     */
    protected function mapConstraint(array $data): ConstraintInterface
    {
        \reset($data);
        $key = \key($data);

        // There is no "default" action, as "and", "or", "not",
        // and "equals" are the only supported constraints.
        // If for whatever reason the value happened to be anything else,
        // this method will throw a TypeError as it will not return
        // an object implementing ConstraintInterface.
        // This is the expected behavior.
        switch ($key) {
            case 'and':
                return new AndConstraint(
                    \array_map([$this, 'mapConstraint'], $data[$key])
                );
            case 'or':
                return new OrConstraint(
                    \array_map([$this, 'mapConstraint'], $data[$key])
                );
            case 'not':
                return new NotConstraint(
                    $this->mapConstraint($data[$key][0])
                );
            case 'paths':
                return new PathsConstraint(
                    $data[$key][0]['doc']
                );
            case 'equals':
                // The $data[$key] array *should* be in the form
                // [{"doc": "sys.type"}, "Entry"]
                // with the object with the "doc" property in the first position,
                // and the actual value in the second position.
                // Just to be safe, we check whether the 'doc' key exists in the first element,
                // so we know that *that* element is the doc, and the other contains the value.
                $docKey = isset($data[$key][0]['doc']) ? 0 : 1;
                $valueKey = 1 - $docKey;

                return new EqualityConstraint(
                    $data[$key][$docKey]['doc'],
                    $data[$key][$valueKey]
                );
            default:
                throw new \RuntimeException(\sprintf(
                    'Trying to build a constraint object using invalid key "%s".',
                    $key
                ));
        }
    }

    /**
     * @param array $data
     *
     * @return Permissions
     */
    protected function mapPermissions(array $data): Permissions
    {
        /** @var Permissions $permissions */
        $permissions = $this->hydrator->hydrate(Permissions::class, [
            'contentDelivery' => $this->convertPermission($data['ContentDelivery']),
            'contentModel' => $this->convertPermission($data['ContentModel']),
            'settings' => $this->convertPermission($data['Settings']),
        ]);

        return $permissions;
    }

    /**
     * @param string|string[] $permission
     *
     * @return string|null
     */
    protected function convertPermission($permission)
    {
        if ($permission === []) {
            return \null;
        }

        if ($permission === ['read']) {
            return 'read';
        }

        // The API will automatically convert
        // ['read', 'manage'] or ['manage']
        // to 'all'
        return 'all';
    }
}
