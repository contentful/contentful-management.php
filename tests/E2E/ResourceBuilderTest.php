<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Management\Resource\Entry;
use Contentful\Tests\Management\BaseTestCase;
use Contentful\Tests\Management\Implementation\FantasticCreatureEntry;
use Contentful\Tests\Management\Implementation\FantasticCreatureEntryMapper;

class ResourceBuilderTest extends BaseTestCase
{
    /**
     * @vcr e2e_resource_builder_add_mappers.json
     */
    public function testAddMappers()
    {
        $client = $this->getClient();
        $proxy = $client->getEnvironmentProxy($this->readOnlySpaceId, 'master');

        $builder = $client->getBuilder();
        $builder->setDataMapperMatcher('Entry', function (array $data) {
            if ('fantasticCreature' === $data['sys']['contentType']['sys']['id']) {
                return FantasticCreatureEntryMapper::class;
            }
        });

        $this->assertSame($builder, $client->getBuilder());

        // This entry has content type 'fantasticCreature'
        // so it should use the custom mapper
        $entry = $proxy->getEntry('4OuC4z6qs0yEWMeqkGmokw');
        $this->assertInstanceOf(FantasticCreatureEntry::class, $entry);
        $this->assertSame('Direwolf', $entry->getName());

        // This entry has content type 'person'
        // so it should default to the regular mapper
        $entry = $proxy->getEntry('3LM5FlCdGUIM0Miqc664q6');
        $this->assertInstanceOf(Entry::class, $entry);
        $this->assertSame('Josh Lyman', $entry->getField('name', 'en-US'));
        $this->assertSame('Chief of Staff', $entry->getField('jobTitle', 'en-US'));
    }
}
