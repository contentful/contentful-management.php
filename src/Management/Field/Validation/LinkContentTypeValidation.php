<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Field\Validation;

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

    public static function getValidFieldTypes(): array
    {
        return ['Link'];
    }

    public static function fromApiResponse(array $data): ValidationInterface
    {
        $contentTypes = $data['linkContentType'];

        return new self($contentTypes);
    }

    public function jsonSerialize()
    {
        return (object) [
            'linkContentType' => $this->contentTypes
        ];
    }
}
