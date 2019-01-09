<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\ClientExtension\Space\Environment;

use Contentful\Core\Resource\ResourceArray;
use Contentful\Management\Query;
use Contentful\Management\Resource\Extension as ResourceClass;
use Contentful\Management\Resource\ResourceInterface;

/**
 * ExtensionExtension trait.
 *
 * This extension is supposed to be applied to Client class which provides a `fetchResource` method.
 *
 * @method ResourceInterface|ResourceArray fetchResource(string $class, array $parameters, Query $query = null, ResourceInterface $resource = null)
 */
trait ExtensionExtension
{
    /**
     * Returns an Extension resource.
     *
     * @param string $spaceId
     * @param string $environmentId
     * @param string $extensionId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/ui-extensions/extension
     */
    public function getExtension(string $spaceId, string $environmentId, string $extensionId): ResourceClass
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
            'environment' => $environmentId,
            'extension' => $extensionId,
        ]);
    }

    /**
     * Returns a ResourceArray object containing Extension resources.
     *
     * @param string $spaceId
     * @param string $environmentId
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/ui-extensions/extensions-collection
     */
    public function getExtensions(string $spaceId, string $environmentId): ResourceArray
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
            'environment' => $environmentId,
        ]);
    }
}
