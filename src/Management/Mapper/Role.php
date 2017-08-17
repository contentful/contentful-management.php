<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Mapper;

use Contentful\Management\Resource\Role as ResourceClass;
use Contentful\Management\SystemProperties;
use Contentful\Management\Role\Policy;
use Contentful\Management\Role\Permissions;
use Contentful\Management\Role\Constraint\OrConstraint;
use Contentful\Management\Role\Constraint\NotConstraint;
use Contentful\Management\Role\Constraint\AndConstraint;
use Contentful\Management\Role\Constraint\EqualityConstraint;
use Contentful\Management\Role\Constraint\ConstraintInterface;

/**
 * Role class.
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
            'policies' => array_map([$this, 'buildPolicy'], $data['policies']),
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
        reset($data);
        $key = key($data);

        switch ($key) {
            case 'and':
                return $this->hydrate(AndConstraint::class, [
                    'children' => array_map([$this, 'buildConstraint'], $data[$key]),
                ]);
            case 'or':
                return $this->hydrate(OrConstraint::class, [
                    'children' => array_map([$this, 'buildConstraint'], $data[$key]),
                ]);
            case 'not':
                return $this->hydrate(NotConstraint::class, [
                    'child' => $this->buildConstraint($data[$key][0]),
                ]);
            case 'equals':
                /**
                 * The $data[$key] array *should* be in the form
                 * [{"doc": "sys.type"}, "Entry"]
                 * with the object with the "doc" property in the first position,
                 * and the actual value in the second position.
                 * Just to be safe, we check whether the 'doc' key exists in the first element,
                 * so we know that *that* element is the doc, and the other contains the value.
                 */
                $docKey = isset($data[$key][0]['doc']) ? 0 : 1;
                $valueKey = 1 - $docKey;

                return $this->hydrate(EqualityConstraint::class, [
                    'doc' => $data[$key][$docKey]['doc'],
                    'value' => $data[$key][$valueKey],
                ]);
            default:
                throw new \RuntimeException('Could not determine the constraint type');
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
            'contentDelivery' => $data['ContentDelivery'],
            'contentModel' => $data['ContentModel'],
            'settings' => $data['Settings'],
        ]);
    }
}
