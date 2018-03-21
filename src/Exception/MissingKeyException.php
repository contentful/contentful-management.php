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
 * MissingKeyException class.
 *
 * A MissingKeyException is thrown when persisting an object
 * without a key that is required.
 */
class MissingKeyException extends Exception
{
    /**
     * @var string
     */
    private $key;

    /**
     * {@inheritdoc}
     */
    public function __construct(RequestException $previous, $message = 'Request body is missing a required key.')
    {
        parent::__construct($previous, $message);

        $result = guzzle_json_decode($this->getResponse()->getBody(), true);

        $this->key = $result['details']['key'];
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }
}
