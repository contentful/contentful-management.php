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

/**
 * ValidationFailedException class.
 *
 * A ValidationFailedException is thrown when persisting an object
 * that's in an invalid state.
 */
class ValidationFailedException extends Exception
{
}
