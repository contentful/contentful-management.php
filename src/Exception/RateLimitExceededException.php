<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Exception;

use Contentful\Core\Exception\RateLimitExceededException as BaseRateLimitExceededException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

/**
 * RateLimitExceededException class.
 *
 * @method ResponseInterface getResponse()
 */
class RateLimitExceededException extends BaseRateLimitExceededException
{
    /**
     * @var int
     */
    private $hourLimit;

    /**
     * @var int
     */
    private $hourRemaining;

    /**
     * @var int
     */
    private $secondLimit;

    /**
     * @var int
     */
    private $secondRemaining;

    /**
     * {@inheritdoc}
     */
    public function __construct(RequestException $previous, $message = '')
    {
        parent::__construct($previous, $message);

        $response = $this->getResponse();

        $this->hourLimit = (int) $response->getHeader('X-Contentful-RateLimit-Hour-Limit')[0];
        $this->hourRemaining = (int) $response->getHeader('X-Contentful-RateLimit-Hour-Remaining')[0];
        $this->secondLimit = (int) $response->getHeader('X-Contentful-RateLimit-Second-Limit')[0];
        $this->secondRemaining = (int) $response->getHeader('X-Contentful-RateLimit-Second-Remaining')[0];
    }

    /**
     * @return int
     */
    public function getHourLimit(): int
    {
        return $this->hourLimit;
    }

    /**
     * @return int
     */
    public function getHourRemaining(): int
    {
        return $this->hourRemaining;
    }

    /**
     * @return int
     */
    public function getSecondLimit(): int
    {
        return $this->secondLimit;
    }

    /**
     * @return int
     */
    public function getSecondRemaining(): int
    {
        return $this->secondRemaining;
    }
}
