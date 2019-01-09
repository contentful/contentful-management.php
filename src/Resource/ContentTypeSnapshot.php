<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource;

/**
 * ContentTypeSnapshot class.
 *
 * This class represents a resource with type "Snapshot" and entity type "ContentType" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/content-type-snapshots-collection
 * @see https://www.contentful.com/faq/versioning/
 *
 * @property ContentType $snapshot
 */
class ContentTypeSnapshot extends Snapshot
{
    /**
     * {@inheritdoc}
     */
    public function asUriParameters(): array
    {
        return [
            'space' => $this->sys->getSpace()->getId(),
            'environment' => $this->sys->getEnvironment()->getId(),
            'contentType' => $this->snapshot->getId(),
            'snapshot' => $this->sys->getId(),
        ];
    }

    /**
     * @return ContentType
     */
    public function getContentType(): ContentType
    {
        return $this->snapshot;
    }
}
