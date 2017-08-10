<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource;

/**
 * ContentTypeSnapshot class.
 *
 * This class represents a resource with type "Snapshot" and entity type "ContentType" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/snapshots/content-type-snapshots-collection
 * @see https://www.contentful.com/faq/versioning/
 */
class ContentTypeSnapshot extends BaseResource
{
    /**
     * @var ContentType
     */
    protected $contentType;

    /**
     * ContentTypeSnapshot constructor.
     */
    final public function __construct()
    {
        throw new \LogicException(sprintf('Class %s can only be instantiated as a result of an API call, manual creation is not allowed.', static::class));
    }

    /**
     * @return ContentType
     */
    public function getContentType(): ContentType
    {
        return $this->contentType;
    }

    /**
     * Returns an array to be used by `json_encode` to serialize objects of this class.
     *
     * @return array
     *
     * @see http://php.net/manual/en/jsonserializable.jsonserialize.php JsonSerializable::jsonSerialize
     */
    public function jsonSerialize(): array
    {
        return [
            'sys' => $this->sys,
            'snapshot' => $this->contentType,
        ];
    }
}
