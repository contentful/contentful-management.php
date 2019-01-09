<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Mapper;

use Contentful\Management\Resource\User as ResourceClass;
use Contentful\Management\SystemProperties\User as SystemProperties;

/**
 * User class.
 *
 * This class is responsible for converting raw API data into a PHP object
 * of class Contentful\Management\Resource\User.
 */
class User extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        if (\null !== $resource) {
            throw new \LogicException(\sprintf(
                'Trying to update resource object in mapper of type "%s", but only creation from scratch is supported.',
                static::class
            ));
        }

        /** @var ResourceClass $user */
        $user = $this->hydrator->hydrate(ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName'],
            'avatarUrl' => $data['avatarUrl'],
            'email' => $data['email'],
            'activated' => $data['activated'],
            'signInCount' => $data['signInCount'],
            'confirmed' => $data['confirmed'],
        ]);

        return $user;
    }
}
