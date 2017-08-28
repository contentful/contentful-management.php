<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Mapper;

use Contentful\Management\Resource\PersonalAccessToken as ResourceClass;
use Contentful\Management\SystemProperties;

/**
 * PersonalAccessToken class.
 */
class PersonalAccessToken extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        return $this->hydrate($resource ?: ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'name' => $data['name'],
            'isReadOnly' => !in_array('content_management_manage', $data['scopes']),
            'revokedAt' => isset($data['revokedAt']) ? new \DateTimeImmutable($data['revokedAt']) : null,
            'token' => $data['token'] ?? null,
        ]);
    }
}
