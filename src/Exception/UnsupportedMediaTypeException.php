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
 * UnsupportedMediaTypeException class.
 *
 * An UnsupportedMediaTypeException is thrown when the API encounters
 * an unknown content type.
 *
 * If it's thrown, it's almost certainly a bug in the SDK; please report it.
 */
class UnsupportedMediaTypeException extends Exception
{
}
