<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Core\File\LocalUploadFile;
use Contentful\Management\Resource\Behavior\CreatableInterface;
use Contentful\Management\Resource\Behavior\DeletableTrait;
use Contentful\Management\SystemProperties\Upload as SystemProperties;
use Psr\Http\Message\StreamInterface;

/**
 * Upload class.
 *
 * This class represents an "Upload" object in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/uploads
 */
class Upload extends BaseResource implements CreatableInterface
{
    use DeletableTrait;

    /**
     * @var SystemProperties
     */
    protected $sys;

    /**
     * @var string|resource|StreamInterface|null
     */
    protected $body;

    /**
     * Upload constructor.
     *
     * @param string|resource|StreamInterface $body Internally this is the value that is passed to Guzzle's request body,
     *                                              which means that all values accepted by Guzzle are allowed. These include an actual "string",
     *                                              a resource such as the result of a "fopen('file.txt', 'r')" call, an object implementing StreamInterface, etc.
     *
     * @see http://docs.guzzlephp.org/en/stable/request-options.html#body For more un Guzzle's internal options
     */
    public function __construct($body)
    {
        $this->body = $body;
    }

    /**
     * {@inheritdoc}
     */
    public function getSystemProperties(): SystemProperties
    {
        return $this->sys;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return [
            'sys' => $this->sys,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function asRequestBody()
    {
        return $this->body;
    }

    /**
     * {@inheritdoc}
     */
    public function asUriParameters(): array
    {
        return [
            'space' => $this->sys->getSpace()->getId(),
            'upload' => $this->sys->getId(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getHeadersForCreation(): array
    {
        return ['Content-Type' => 'application/octet-stream'];
    }

    /**
     * @return string|resource|StreamInterface|null
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string|resource|StreamInterface|null $body
     *
     * @return static
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Returns an object representation of the upload that is compatible to an asset resource.
     *
     * @param string $filename
     * @param string $contentType
     *
     * @return LocalUploadFile
     */
    public function asAssetFile(string $filename, string $contentType): LocalUploadFile
    {
        return new LocalUploadFile($filename, $contentType, $this->asLink());
    }
}
