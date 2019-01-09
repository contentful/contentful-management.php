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
 * InternalServerErrorException class.
 *
 * An InternalServerErrorException is thrown when the API encountered
 * an unexpected error while processing the request.
 * Please contact support to resolve this issue.
 */
class InternalServerErrorException extends Exception
{
}
