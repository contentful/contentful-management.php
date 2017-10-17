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

/**
 * UnsupportedMediaTypeException class.
 *
 * An UnsupportedMediaTypeException is thrown when the API encounters
 * an unknown content type.
 *
 * If it's thrown, it's almost certainly a bug in the SDK; please report it.
 */
class UnsupportedMediaTypeException extends ApiException
{
}
