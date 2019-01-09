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
    private function __construct()
    {
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

    /**
     * {@inheritdoc}
     */
    public function asUriParameters(): array
    {
        return [
            'space' => $this->sys->getSpace()->getId(),
            'previewApiKey' => $this->sys->getId(),
        ];
    }
}
