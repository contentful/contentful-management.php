<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\ContentType\Validation;

/**
 * LinkContentTypeValidation class.
 *
 * Takes an array of content type IDs and validates that
 * the link points to an entry of one of those content types.
 *
 * Applicable to:
 * - Link (to entries)
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
     * @return static
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
    public function jsonSerialize(): array
    {
        return [
            'linkContentType' => $this->contentTypes,
        ];
    }
}
