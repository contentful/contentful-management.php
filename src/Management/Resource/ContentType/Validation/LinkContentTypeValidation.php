<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource\ContentType\Validation;

/**
 * LinkContentTypeValidation class.
 */
class LinkContentTypeValidation implements ValidationInterface
{
    /**
     * @var string[]
     */
    private $contentTypes = [];

    /**
     * LinkContentTypeValidation constructor.
     *
     * @param string[] $contentTypes
     */
    public function __construct(array $contentTypes = [])
    {
        $this->contentTypes = $contentTypes;
    }

    /**
     * @return string[]
     */
    public function getContentTypes(): array
    {
        return $this->contentTypes;
    }

    /**
     * @param string[] $contentTypes
     *
     * @return $this
     */
    public function setContentTypes(array $contentTypes)
    {
        $this->contentTypes = $contentTypes;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function getValidFieldTypes(): array
    {
        return ['Link'];
    }

    /**
     * {@inheritdoc}
     */
    public static function fromApiResponse(array $data): ValidationInterface
    {
        $contentTypes = $data['linkContentType'];

        return new self($contentTypes);
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
            'linkContentType' => $this->contentTypes,
        ];
    }
}
