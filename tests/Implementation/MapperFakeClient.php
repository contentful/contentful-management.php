<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Implementation;

use Contentful\Core\Api\Link;
use Contentful\Management\Client;
use Contentful\Management\Resource\Asset;
use Contentful\Management\Resource\ResourceInterface;
use Contentful\Tests\Management\Fixtures\Integration\CodeGenerator\BlogPost;

class MapperFakeClient extends Client
{
    public function resolveLink(Link $link, array $parameters = []): ResourceInterface
    {
        if ('Asset' === $link->getLinkType()) {
            return new Asset();
        }

        if ('Entry' === $link->getLinkType()) {
            return new BlogPost();
        }
    }
}
