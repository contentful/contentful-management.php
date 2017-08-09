<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource;

use Contentful\Link;
use Contentful\Management\Behavior\Creatable;
use Contentful\Management\Behavior\Deletable;
use Contentful\Management\Behavior\Linkable;
use Contentful\Management\SystemProperties;
use Psr\Http\Message\StreamInterface;

/**
 * Upload class.
 *
 * This class represents an "Upload" object in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/uploads
 */
class Upload implements SpaceScopedResourceInterface, Creatable, Deletable, Linkable
{
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
     *                                              which means that all values accepted by Guzzle are allowed. These include an actual `string`,
     *                                              a `resource` such as the result of a `fopen('file.txt', 'r')` call, an object implementing StreamInterface, etc.
     *
     * @see http://docs.guzzlephp.org/en/stable/request-options.html#body For more un Guzzle's internal options
     */
    public function __construct($body)
    {
        $this->sys = SystemProperties::withType('Upload');
        $this->body = $body;
    }

    /**
     * {@inheritdoc}
     */
    public function getResourceUriPart(): string
    {
        return 'uploads';
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
    public function asLink(): Link
    {
        return new Link($this->sys->getId(), 'Upload');
    }

    /**
     * @return string|resource|StreamInterface|null
     */
    public function getBody()
    {
        return $this->body;
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
        ];
    }
}
