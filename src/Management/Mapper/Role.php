<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
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
use Contentful\Management\Resource\Role\Permissions;
use Contentful\Management\Resource\Role\Policy;
use Contentful\Management\SystemProperties;

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
        return $this->hydrate($resource ?: ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'name' => $data['name'],
            'description' => $data['description'],
            'policies' => \array_map([$this, 'buildPolicy'], $data['policies']),
            'permissions' => $this->buildPermissions($data['permissions']),
        ]);
    }

    /**
     * @param array $data
     *
     * @return Policy
     */
    protected function buildPolicy(array $data): Policy
    {
        return $this->hydrate(Policy::class, [
            'effect' => $data['effect'],
            'actions' => $data['actions'],
            'constraint' => isset($data['constraint']) ? $this->buildConstraint($data['constraint']) : null,
        ]);
    }

    /**
     * @param array $data
     *
     * @return ConstraintInterface
     */
    protected function buildConstraint(array $data): ConstraintInterface
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
                return $this->hydrate(AndConstraint::class, [
                    'children' => \array_map([$this, 'buildConstraint'], $data[$key]),
                ]);
            case 'or':
                return $this->hydrate(OrConstraint::class, [
                    'children' => \array_map([$this, 'buildConstraint'], $data[$key]),
                ]);
            case 'not':
                return $this->hydrate(NotConstraint::class, [
                    'child' => $this->buildConstraint($data[$key][0]),
                ]);
            case 'equals':
                // The $data[$key] array *should* be in the form
                // [{"doc": "sys.type"}, "Entry"]
                // with the object with the "doc" property in the first position,
                // and the actual value in the second position.
                // Just to be safe, we check whether the 'doc' key exists in the first element,
                // so we know that *that* element is the doc, and the other contains the value.
                $docKey = isset($data[$key][0]['doc']) ? 0 : 1;
                $valueKey = 1 - $docKey;

                return $this->hydrate(EqualityConstraint::class, [
                    'doc' => $data[$key][$docKey]['doc'],
                    'value' => $data[$key][$valueKey],
                ]);
            default:
                throw new \RuntimeException(sprintf(
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
    protected function buildPermissions(array $data): Permissions
    {
        return $this->hydrate(Permissions::class, [
            'contentDelivery' => $this->convertPermission($data['ContentDelivery']),
            'contentModel' => $this->convertPermission($data['ContentModel']),
            'settings' => $this->convertPermission($data['Settings']),
        ]);
    }

    /**
     * @param string|string[] $permission
     *
     * @return string|null
     */
    protected function convertPermission($permission)
    {
        if ($permission === []) {
            return null;
        }

        if ($permission == ['read']) {
            return 'read';
        }

        // The API will automatically convert
        // ['read', 'manage'] or ['manage']
        // to 'all'
        return 'all';
    }
}
