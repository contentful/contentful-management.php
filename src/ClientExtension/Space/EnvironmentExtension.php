<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\ClientExtension\Space;

use Contentful\Core\Resource\ResourceArray;
use Contentful\Management\Proxy\EnvironmentProxy;
use Contentful\Management\Query;
use Contentful\Management\Resource\Environment as ResourceClass;
use Contentful\Management\Resource\ResourceInterface;

/**
 * EnvironmentExtension trait.
 *
 * This extension is supposed to be applied to Client class which provides a `fetchResource` method.
 *
 * @method ResourceInterface|ResourceArray fetchResource(string $class, array $parameters, Query $query = null, ResourceInterface $resource = null)
 */
trait EnvironmentExtension
{
    use Environment\AssetExtension;
    use
        Environment\ContentTypeExtension;
    use
        Environment\EntryExtension;
    use
        Environment\ExtensionExtension;
    use
        Environment\LocaleExtension;

    /**
     * Returns a proxy to an environment resource.
     * Useful for all environment-scoped operations.
     */
    public function getEnvironmentProxy(string $spaceId, string $environmentId = 'master'): EnvironmentProxy
    {
        return new EnvironmentProxy($this, $spaceId, $environmentId);
    }

    /**
     * Returns an Environment resource.
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/environments/environment
     */
    public function getEnvironment(string $spaceId, string $environmentId): ResourceClass
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
            'environment' => $environmentId,
        ]);
    }

    /**
     * Returns a ResourceArray object which contains Environment resources.
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/environments/environments-collection
     */
    public function getEnvironments(string $spaceId, Query $query = null): ResourceArray
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
        ], $query);
    }
}
