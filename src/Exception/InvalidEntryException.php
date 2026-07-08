<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2022 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Exception;

use Contentful\Core\Api\Exception;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\json_decode as guzzle_json_decode;

/**
 * RateLimitExceededException class.
 *
 * @method ResponseInterface getResponse()
 */
class InvalidEntryException extends Exception
{


    /**
     * Validation Errors.
     *
     * @var array
     */
    private $errors = [];


    /**
     * {@inheritdoc}
     */
    public function __construct(RequestException $previous, $message = '')
    {
        parent::__construct($previous, $message);

        $previousBody = guzzle_json_decode((string) $previous->getResponse()->getBody());
        if ($previousBody?->details) {
            $this->errors = $previousBody->details->errors;
        }
    }

    /**
     * Get error details.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

}
