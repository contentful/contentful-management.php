<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Mapper;

use Contentful\Core\Api\Link;
use Contentful\Management\Resource\SpaceMembership as ResourceClass;
use Contentful\Management\SystemProperties\SpaceMembership as SystemProperties;

/**
 * SpaceMembership class.
 *
 * This class is responsible for converting raw API data into a PHP object
 * of class Contentful\Management\Resource\SpaceMembership.
 */
class SpaceMembership extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        /** @var ResourceClass $spaceMembership */
        $spaceMembership = $this->hydrator->hydrate($resource ?: ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'admin' => $data['admin'],
            'email' => $data['email'] ?? \null,
            'roles' => \array_map(function (array $role): Link {
                return new Link($role['sys']['id'], 'Role');
            }, $data['roles'] ?? []),
            'user' => new Link($data['user']['sys']['id'], 'User'),
        ]);

        return $spaceMembership;
    }
}
