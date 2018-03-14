<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\ClientExtension\Space\ContentType;

use Contentful\Management\Resource\EditorInterface as ResourceClass;

/**
 * EditorInterfaceExtension trait.
 *
 * This extension is supposed to be applied to Client class which provides a `fetchResource` method.
 *
 * @method ResourceInterface|ResourceArray fetchResource(string $class, array $parameters, Query $query = null, ResourceInterface $resource = null)
 */
trait EditorInterfaceExtension
{
    /**
     * Returns an EditorInterface resource.
     *
     * @param string $spaceId
     * @param string $contentTypeId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/editor-interface
     */
    public function getEditorInterface(string $spaceId, string $contentTypeId): ResourceClass
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
            'contentType' => $contentTypeId,
        ]);
    }
}
