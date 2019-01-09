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
use Contentful\Management\ApiConfiguration;
use Contentful\Management\Query;
use Contentful\Management\Resource\ContentType as ResourceClass;
use Contentful\Management\Resource\ResourceInterface;

/**
 * ContentTypeExtension trait.
 *
 * This extension is supposed to be applied to Client class which provides a `fetchResource` method.
 *
 * @method ResourceInterface|ResourceArray fetchResource(string $class, array $parameters, Query $query = null, ResourceInterface $resource = null)
 */
trait ContentTypeExtension
{
    use ContentType\ContentTypeSnapshotExtension,
        ContentType\EditorInterfaceExtension;

    /**
     * Returns a ContentType resource.
     *
     * @param string $spaceId
     * @param string $environmentId
     * @param string $contentTypeId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/content-types/content-type
     */
    public function getContentType(string $spaceId, string $environmentId, string $contentTypeId): ResourceClass
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
            'environment' => $environmentId,
            'contentType' => $contentTypeId,
        ]);
    }

    /**
     * Returns a ResourceArray object which contains ContentType resources.
     *
     * @param string     $spaceId
     * @param string     $environmentId
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/content-types/content-type-collection
     */
    public function getContentTypes(string $spaceId, string $environmentId, Query $query = \null): ResourceArray
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
            'environment' => $environmentId,
        ], $query);
    }

    /**
     * Returns a published ContentType resource.
     *
     * @param string $spaceId
     * @param string $environmentId
     * @param string $contentTypeId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/content-types/activated-content-type-collection
     */
    public function getPublishedContentType(string $spaceId, string $environmentId, string $contentTypeId): ResourceClass
    {
        return $this->fetchResource(ApiConfiguration::PUBLISHED_CONTENT_TYPE_RESOURCE, [
            'space' => $spaceId,
            'environment' => $environmentId,
            'contentType' => $contentTypeId,
        ]);
    }

    /**
     * Returns a ResourceArray object which contains published ContentType resources.
     *
     * @param string     $spaceId
     * @param string     $environmentId
     * @param Query|null $query
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/content-types/activated-content-type-collection
     */
    public function getPublishedContentTypes(string $spaceId, string $environmentId, Query $query = \null): ResourceArray
    {
        return $this->fetchResource(ApiConfiguration::PUBLISHED_CONTENT_TYPE_RESOURCE, [
            'space' => $spaceId,
            'environment' => $environmentId,
        ], $query);
    }
}
