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

/**
 * VersionMismatchException class.
 *
 * A VersionMismatchException is thrown when persisting an object
 * that has changed on the server since it's been fetched.
 */
class VersionMismatchException extends ApiException
{
    /**
     * {@inheritdoc}
     */
    public function __construct(GuzzleRequestException $previous, $message = 'The version number you supplied is invalid.')
    {
        parent::__construct($previous, $message);
    }
}
