<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Resource;

/**
 * PreviewApiKey class.
 *
 * This class represents a resource with type "PreviewApiKey" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/api-keys
 */
class PreviewApiKey extends ApiKey
{
    /**
     * PreviewApiKey constructor.
     */
    final public function __construct()
    {
        throw new \LogicException(\sprintf(
            'Class "%s" can only be instantiated as a result of an API call, manual creation is not allowed.',
            static::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function asRequestBody()
    {
        throw new \LogicException(\sprintf(
            'Trying to convert object of class "%s" to a request body format, but operation is not supported on this class.',
            static::class
        ));
    }
}
