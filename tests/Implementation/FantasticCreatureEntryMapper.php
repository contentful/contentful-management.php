<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Implementation;

use Contentful\Management\Mapper\BaseMapper;
use Contentful\Management\SystemProperties\Entry as SystemProperties;

class FantasticCreatureEntryMapper extends BaseMapper
{
    public function map($resource, array $data): FantasticCreatureEntry
    {
        return $this->hydrator->hydrate($resource ?? FantasticCreatureEntry::class, [
            'sys' => new SystemProperties($data['sys']),
            'fields' => $data['fields'] ?? [],
        ]);
    }
}
