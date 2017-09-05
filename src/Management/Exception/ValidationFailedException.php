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
 * ValidationFailedException class.
 *
 * A ValidationFailedException is thrown when persisting an object
 * that's in an invalid state.
 */
class ValidationFailedException extends ApiException
{
}
