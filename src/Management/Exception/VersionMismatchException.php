<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Exception;

use Contentful\Exception\ApiException;

/**
 * A VersionMismatchException is thrown when persisting an object that has changed on the server since it's been fetched.
 */
class VersionMismatchException extends ApiException
{
}
