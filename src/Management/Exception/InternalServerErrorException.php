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
 * InternalServerErrorException class.
 *
 * An InternalServerErrorException is thrown when the API encountered
 * an unexpected error while processing the request.
 * Please contact support to resolve this issue.
 */
class InternalServerErrorException extends ApiException
{
}
