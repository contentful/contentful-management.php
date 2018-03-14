<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\ClientExtension;

use Contentful\Management\Resource\User as ResourceClass;

/**
 * UserExtension trait.
 *
 * This extension is supposed to be applied to Client class which provides a `fetchResource` method.
 *
 * @method ResourceInterface|ResourceArray fetchResource(string $class, array $parameters, Query $query = null, ResourceInterface $resource = null)
 */
trait UserExtension
{
    use User\PersonalAccessTokenExtension;

    /**
     * Returns a User resource.
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/users/user
     */
    public function getUserMe(): ResourceClass
    {
        return $this->fetchResource(ResourceClass::class, [
            'user' => 'me',
        ]);
    }
}
