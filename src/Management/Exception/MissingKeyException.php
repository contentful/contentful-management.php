<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Exception;

use Contentful\Exception\ApiException;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use function GuzzleHttp\json_decode;

/**
 * MissingKeyException class.
 *
 * A MissingKeyException is thrown when persisting an object
 * without a key that is required.
 */
class MissingKeyException extends ApiException
{
    /**
     * @var string
     */
    private $key;

    /**
     * {@inheritdoc}
     */
    public function __construct(GuzzleRequestException $previous, $message = 'Request body is missing a required key.')
    {
        parent::__construct($previous, $message);

        $result = json_decode($this->getResponse()->getBody(), true);

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
