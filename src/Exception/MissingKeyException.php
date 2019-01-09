<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Exception;

use Contentful\Core\Api\Exception;
use GuzzleHttp\Exception\RequestException;
use function GuzzleHttp\json_decode as guzzle_json_decode;
use Psr\Http\Message\ResponseInterface;

/**
 * MissingKeyException class.
 *
 * A MissingKeyException is thrown when persisting an object
 * without a key that is required.
 *
 * @method ResponseInterface getResponse()
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

        $result = guzzle_json_decode((string) $this->getResponse()->getBody(), \true);

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
