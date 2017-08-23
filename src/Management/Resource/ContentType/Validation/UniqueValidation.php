<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource\ContentType\Validation;

/**
 * UniqueValidation class.
 */
class UniqueValidation implements ValidationInterface
{
    /**
     * UniqueValidation constructor.
     *
     * Empty constructor for forward compatibility
     */
    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function fromApiResponse(array $data): ValidationInterface
    {
        return new self();
    }

    /**
     * {@inheritdoc}
     */
    public static function getValidFieldTypes(): array
    {
        return ['Symbol', 'Integer', 'Number'];
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
            'unique' => true,
        ];
    }
}
