<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Exception;

use Contentful\Exception\ApiException;
use Contentful\JsonHelper;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;

/**
 * A MissingKeyException is thrown when persisting an object without a key that is required.
 */
class MissingKeyException extends ApiException
{
    /**
     * @var string
     */
    private $key;

    public function __construct(GuzzleRequestException $previous, $message = '')
    {
        parent::__construct($previous, $message);

        $result = JsonHelper::decode($this->getResponse()->getBody());

        $this->key = $result['details']['key'];
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
