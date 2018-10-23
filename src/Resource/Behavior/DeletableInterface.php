<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\Behavior;

use Contentful\Management\Resource\ResourceInterface;

/**
 * DeletableInterface.
 *
 * This interface is supposed to be implemented by resources that can be archived.
 */
interface DeletableInterface extends ResourceInterface
{
}
