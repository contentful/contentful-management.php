<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Field\Validation;

class LinkMimetypeGroupValidation implements ValidationInterface
{
    /**
     * @var string[]
     */
    private $mimeTypeGroups = [];

    /**
     * LinkMimetypeGroupValidation constructor.
     *
     * @param string[] $mimeTypeGroups
     */
    public function __construct(array $mimeTypeGroups = [])
    {
        $this->mimeTypeGroups = $mimeTypeGroups;
    }

    /**
     * @return string[]
     */
    public function getMimeTypeGroups(): array
    {
        return $this->mimeTypeGroups;
    }

    /**
     * @param string[] $mimeTypeGroups
     *
     * @return $this
     */
    public function setMimeTypeGroups(array $mimeTypeGroups)
    {
        $this->mimeTypeGroups = $mimeTypeGroups;

        return $this;
    }

    public static function getValidFieldTypes(): array
    {
        return ['Link'];
    }

    public static function fromApiResponse(array $data): ValidationInterface
    {
        $mimeTypeGroups = $data['linkMimetypeGroup'];

        return new self($mimeTypeGroups);
    }

    public function jsonSerialize()
    {
        return (object) [
            'linkMimetypeGroup' => $this->mimeTypeGroups
        ];
    }
}
