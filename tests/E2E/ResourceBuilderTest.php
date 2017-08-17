<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\E2E;

use Contentful\Management\Mapper\BaseMapper;
use Contentful\Management\Resource\Entry;
use Contentful\Management\ResourceBuilder;
use Contentful\Management\SystemProperties;
use Contentful\Tests\End2EndTestCase;

class ResourceBuilderTest extends End2EndTestCase
{
    /**
     * @vcr e2e_resource_builder_add_mappers.json
     */
    public function testAddCustomMappers()
    {
        $builder = new ResourceBuilder();
        $builder->setDataMapperMatcher('Entry', function (array $data) {
            if ($data['sys']['type'] == 'Entry' && $data['sys']['contentType']['sys']['id'] == 'fantasticCreature') {
                return FantasticCreatureEntryMapper::class;
            }
        });

        $this->client->setBuilder($builder);

        $this->assertSame($builder, $this->client->getBuilder());

        $manager = $this->getReadWriteSpaceManager();

        // This entry has content type 'fantasticCreature'
        // so it should use the custom mapper
        $entry = $manager->getEntry('4OuC4z6qs0yEWMeqkGmokw');
        $this->assertInstanceOf(FantasticCreatureEntry::class, $entry);
        $this->assertEquals('Direwolf', $entry->getName());

        // This entry has content type 'person'
        // so it should default to the regular mapper
        $entry = $manager->getEntry('3LM5FlCdGUIM0Miqc664q6');
        $this->assertInstanceOf(Entry::class, $entry);
        $this->assertEquals('Josh Lyman', $entry->getField('name', 'en-US'));
        $this->assertEquals('Chief of Staff', $entry->getField('jobTitle', 'en-US'));
    }
}

class FantasticCreatureEntryMapper extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): FantasticCreatureEntry
    {
        return $this->hydrate($resource ?? FantasticCreatureEntry::class, [
            'sys' => new SystemProperties($data['sys']),
            'fields' => $data['fields'] ?? [],
        ]);
    }
}

class FantasticCreatureEntry extends Entry
{
    /**
     * @param string $locale
     *
     * @return string|null
     */
    public function getName(string $locale = 'en-US')
    {
        return $this->getField('name', $locale);
    }
}
