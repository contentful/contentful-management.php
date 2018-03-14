<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\ClientExtension\Space;

use Contentful\Management\Resource\Upload as ResourceClass;

/**
 * UploadExtension trait.
 *
 * This extension is supposed to be applied to Client class which provides a `fetchResource` method.
 *
 * @method ResourceInterface|ResourceArray fetchResource(string $class, array $parameters, Query $query = null, ResourceInterface $resource = null)
 */
trait UploadExtension
{
    /**
     * Returns an Upload resource.
     *
     * @param string $spaceId
     * @param string $uploadId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/uploads/retrieving-an-upload
     */
    public function getUpload(string $spaceId, string $uploadId): ResourceClass
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
            'upload' => $uploadId,
        ]);
    }
}
