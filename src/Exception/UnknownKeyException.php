<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Exception;

use Contentful\Core\Api\Exception;
use GuzzleHttp\Exception\RequestException;
use function GuzzleHttp\json_decode as guzzle_json_decode;

/**
 * UnknownKeyException class.
 *
 * An UnknownKeyException is thrown when persisting an object with a key
 * that is not recognized by the API.
 */
class UnknownKeyException extends Exception
{
    /**
     * @var string[]
     */
    private $keys = [];

    /**
     * UnknownKeyException constructor.
     *
     * @param RequestException $previous
     * @param string           $message
     */
    public function __construct(RequestException $previous, $message = '')
    {
        parent::__construct($previous, $message);

        $result = guzzle_json_decode($this->getResponse()->getBody(), true);

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
