<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Management\Resource\Behavior\Creatable;
use Contentful\Management\Resource\Behavior\Deletable;
use Psr\Http\Message\StreamInterface;

/**
 * Upload class.
 *
 * This class represents an "Upload" object in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/uploads
 */
class Upload extends BaseResource implements Creatable, Deletable
{
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
        parent::__construct('Upload');
        $this->body = $body;
    }

    /**
     * Returns an array to be used by "json_encode" to serialize objects of this class.
     *
     * @return array
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
}
