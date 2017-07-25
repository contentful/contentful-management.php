<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Exception;

use function GuzzleHttp\json_decode;
use Contentful\Exception\ApiException;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;

/**
 * An UnknownKeyException is thrown when persisting an object with a key that is not recognized by the API.
 */
class UnknownKeyException extends ApiException
{
    /**
     * @var string[]
     */
    private $keys = [];

    public function __construct(GuzzleRequestException $previous, $message = '')
    {
        parent::__construct($previous, $message);

        $result = json_decode($this->getResponse()->getBody(), true);

        $this->keys = $result['details']['keys'];
    }

    /**
     * @return string[]
     */
    public function getKeys(): array
    {
        return $this->keys;
    }
}
