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

/**
 * VersionMismatchException class.
 *
 * A VersionMismatchException is thrown when persisting an object
 * that has changed on the server since it's been fetched.
 */
class VersionMismatchException extends Exception
{
    /**
     * {@inheritdoc}
     */
    public function __construct(RequestException $previous, $message = 'The version number you supplied is invalid.')
    {
        parent::__construct($previous, $message);
    }
}
