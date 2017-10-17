<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Proxy;

use Contentful\Management\Resource\EditorInterface as ResourceClass;

/**
 * EditorInterface class.
 *
 * @method ResourceInterface update(\Contentful\Management\Resource\EditorInterface $resource)
 */
class EditorInterface extends BaseProxy
{
    /**
     * {@inheritdoc}
     */
    protected function getResourceUri(array $values): string
    {
        return \rtrim(\strtr('spaces/'.$this->spaceId.'/content_types/{resourceId}/editor_interface', $values), '/');
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabledMethods(): array
    {
        return ['update'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getResourceId($resource): string
    {
        // We override the default ID retrieval method
        // because editor interfaces don't have an ID on their own
        // but rather use their content type's ID.
        // We are sure that $resource is an object of type EditorInterface,
        // because the only enabled method is "update",
        // which requires a resource class.
        // It would be nice if PHP allowed us to override the method signature
        // and typehint $resource with "ResourceClass", but alas,
        // that's not possible.
        return $resource->getSystemProperties()->getContentType()->getId();
    }

    /**
     * Returns an EditorInterface object which corresponds to the given content type's ID in Contentful.
     *
     * @param string $resourceId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/editor-interface
     */
    public function get(string $resourceId): ResourceClass
    {
        return $this->getResource([
            '{resourceId}' => $resourceId,
        ]);
    }
}
