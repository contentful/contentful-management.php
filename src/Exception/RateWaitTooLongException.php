<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Exception;

use Contentful\Core\Api\Exception;
use GuzzleHttp\Exception\RequestException;

/**
 * A RateWaitTooLongException is thrown when the X-Contentful-RateLimit-Reset has a value of 60 or more.
 * We do not automatically retry in that case to prevent never ending scripts.
 */
class RateWaitTooLongException extends Exception
{
    /**
     * @var int|null
     */
    private $rateLimitReset;

    /**
     * RateWaitTooLongException constructor.
     */
    public function __construct(RequestException $previous, string $message = '')
    {
        parent::__construct($previous, $message);

        $response = $this->getResponse();
        if ($response) {
            $this->rateLimitReset = (int) $response->getHeader('X-Contentful-RateLimit-Reset')[0];
        }
    }
}
