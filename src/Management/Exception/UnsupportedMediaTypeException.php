<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Exception;

use Contentful\Exception\ApiException;

/**
 * An UnsupportedMediaTypeException is thrown when the API encounters a Content-Type that it's not aware of.
 * If it's thrown it's almost certainly a bug in the SDK, please report it.
 */
class UnsupportedMediaTypeException extends ApiException
{
}
