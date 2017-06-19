<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Exception;

use Contentful\Exception\ApiException;

/**
 * An InternalServerErrorException is thrown when the API encountered an unexpected error while processing the request.
 * Please contact support to resolve this issue.
 */
class InternalServerErrorException extends ApiException
{
}
