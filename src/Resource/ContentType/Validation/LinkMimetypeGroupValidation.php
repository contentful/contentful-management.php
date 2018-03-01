<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Resource\ContentType\Validation;

/**
 * LinkMimetypeGroupValidation class.
 *
 * Takes a MimeType group name and validates that
 * the link points to an asset of this group.
 *
 * Applicable to:
 * - Link (to assets)
 */
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
     * @return static
     */
    public function setMimeTypeGroups(array $mimeTypeGroups)
    {
        $this->mimeTypeGroups = $mimeTypeGroups;

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
     * Returns an array to be used by "json_encode" to serialize objects of this class.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'linkMimetypeGroup' => $this->mimeTypeGroups,
        ];
    }
}
