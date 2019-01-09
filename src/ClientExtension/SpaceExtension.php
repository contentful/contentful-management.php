<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\ClientExtension;

use Contentful\Core\Resource\ResourceArray;
use Contentful\Management\Proxy\SpaceProxy;
use Contentful\Management\Query;
use Contentful\Management\Resource\ResourceInterface;
use Contentful\Management\Resource\Space as ResourceClass;

/**
 * SpaceExtension trait.
 *
 * This extension is supposed to be applied to Client class which provides a `fetchResource` method.
 *
 * @method ResourceInterface|ResourceArray fetchResource(string $class, array $parameters, Query $query = null, ResourceInterface $resource = null)
 */
trait SpaceExtension
{
    use Space\DeliveryApiKeyExtension,
        Space\EnvironmentExtension,
        Space\PreviewApiKeyExtension,
        Space\RoleExtension,
        Space\SpaceMembershipExtension,
        Space\UploadExtension,
        Space\WebhookExtension;

    /**
     * Returns a proxy to a space resource.
     * Useful for all space-scoped operations.
     *
     * @param string $spaceId
     *
     * @return SpaceProxy
     */
    public function getSpaceProxy(string $spaceId): SpaceProxy
    {
        return new SpaceProxy($this, $spaceId);
    }

    /**
     * Returns a Space resource.
     *
     * @param string $spaceId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/spaces/space
     */
    public function getSpace(string $spaceId): ResourceClass
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
        ]);
    }

    /**
     * Returns a ResourceArray object containing Space objects.
     *
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/spaces/spaces-collection
     */
    public function getSpaces(Query $query = \null): ResourceArray
    {
        return $this->fetchResource(ResourceClass::class, [], $query);
    }
}
