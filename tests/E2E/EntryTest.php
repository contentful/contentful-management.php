<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Core\Api\DateTimeImmutable;
use Contentful\Core\Api\Link;
use Contentful\Management\Query;
use Contentful\Management\Resource\Entry;
use Contentful\Tests\Management\BaseTestCase;

class EntryTest extends BaseTestCase
{
    /**
     * @vcr e2e_entry_get_one.json
     */
    public function testGetOne()
    {
        $proxy = $this->getReadOnlyEnvironmentProxy();

        $entry = $proxy->getEntry('4OuC4z6qs0yEWMeqkGmokw');

        $this->assertSame('Direwolf', $entry->getField('name', 'en-US'));
        $this->assertLink('4OuC4z6qs0yEWMeqkGmokw', 'Entry', $entry->asLink());

        $sys = $entry->getSystemProperties();
        $this->assertSame('4OuC4z6qs0yEWMeqkGmokw', $sys->getId());
        $this->assertSame('Entry', $sys->getType());
        $this->assertLink('fantasticCreature', 'ContentType', $sys->getContentType());
        $this->assertSame(6, $sys->getVersion());
        $this->assertLink($this->readOnlySpaceId, 'Space', $sys->getSpace());
        $this->assertSame('2017-08-22T11:50:19.841Z', (string) $sys->getCreatedAt());
        $this->assertSame('2017-08-22T11:50:27.087Z', (string) $sys->getUpdatedAt());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getCreatedBy());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getUpdatedBy());
        $this->assertSame(5, $sys->getPublishedVersion());
        $this->assertSame(1, $sys->getPublishedCounter());
        $this->assertSame('2017-08-22T11:50:26.990Z', (string) $sys->getPublishedAt());
        $this->assertSame('2017-08-22T11:50:26.990Z', (string) $sys->getFirstPublishedAt());
    }

    /**
     * @vcr e2e_entry_get_one_from_space_proxy.json
     */
    public function testGetOneFromSpaceProxy()
    {
        $proxy = $this->getReadOnlySpaceProxy();

        $entry = $proxy->getEntry('master', '4OuC4z6qs0yEWMeqkGmokw');

        $this->assertSame('Direwolf', $entry->getField('name', 'en-US'));
        $this->assertLink('4OuC4z6qs0yEWMeqkGmokw', 'Entry', $entry->asLink());

        $sys = $entry->getSystemProperties();
        $this->assertSame('4OuC4z6qs0yEWMeqkGmokw', $sys->getId());
        $this->assertSame('Entry', $sys->getType());
        $this->assertLink('fantasticCreature', 'ContentType', $sys->getContentType());
        $this->assertSame(6, $sys->getVersion());
        $this->assertLink($this->readOnlySpaceId, 'Space', $sys->getSpace());
        $this->assertSame('2017-08-22T11:50:19.841Z', (string) $sys->getCreatedAt());
        $this->assertSame('2017-08-22T11:50:27.087Z', (string) $sys->getUpdatedAt());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getCreatedBy());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getUpdatedBy());
        $this->assertSame(5, $sys->getPublishedVersion());
        $this->assertSame(1, $sys->getPublishedCounter());
        $this->assertSame('2017-08-22T11:50:26.990Z', (string) $sys->getPublishedAt());
        $this->assertSame('2017-08-22T11:50:26.990Z', (string) $sys->getFirstPublishedAt());
    }

    /**
     * @vcr e2e_entry_get_one_from_environment.json
     */
    public function testGetOneFromEnvironment()
    {
        $environment = $this->getReadOnlyEnvironmentProxy()->toResource();

        $entry = $environment->getEntry('4OuC4z6qs0yEWMeqkGmokw');
        $sys = $entry->getSystemProperties();
        $this->assertSame('4OuC4z6qs0yEWMeqkGmokw', $sys->getId());
        $this->assertSame('Entry', $sys->getType());
        $this->assertLink('fantasticCreature', 'ContentType', $sys->getContentType());
    }

    /**
     * @vcr e2e_entry_get_collection.json
     */
    public function testGetCollection()
    {
        $proxy = $this->getReadOnlyEnvironmentProxy();
        $entries = $proxy->getEntries();

        $this->assertInstanceOf(Entry::class, $entries[0]);

        $query = (new Query())
            ->setLimit(1)
        ;
        $entries = $proxy->getEntries($query);
        $this->assertInstanceOf(Entry::class, $entries[0]);
        $this->assertCount(1, $entries);
    }

    /**
     * @vcr e2e_entry_get_collection_from_space_proxy.json
     */
    public function testGetCollectionFromSpaceProxy()
    {
        $proxy = $this->getReadOnlySpaceProxy();
        $entries = $proxy->getEntries('master');

        $this->assertInstanceOf(Entry::class, $entries[0]);

        $query = (new Query())
            ->setLimit(1)
        ;
        $entries = $proxy->getEntries('master', $query);
        $this->assertInstanceOf(Entry::class, $entries[0]);
        $this->assertCount(1, $entries);
    }

    /**
     * @vcr e2e_entry_create_update_publish_unpublish_archive_unarchive_delete.json
     */
    public function testCreateUpdatePublishUnpublishArchiveUnarchiveDelete()
    {
        $proxy = $this->getReadWriteEnvironmentProxy();

        $entry = (new Entry('testCt'))
            ->setField('name', 'en-US', 'A name')
        ;

        $proxy->create($entry);
        $this->assertNotNull($entry->getId());
        $this->assertSame(['name' => 'A name'], $entry->getFields('en-US'));
        $this->assertSame(['name' => ['en-US' => 'A name']], $entry->getFields());
        $this->assertTrue($entry->getSystemProperties()->isDraft());
        $this->assertFalse($entry->getSystemProperties()->isPublished());
        $this->assertFalse($entry->getSystemProperties()->isUpdated());
        $this->assertFalse($entry->getSystemProperties()->isArchived());

        $entry->setName('en-US', 'A better name');

        $entry->update();
        $this->assertSame('A better name', $entry->getName('en-US'));

        $entry->archive();
        $this->assertSame(2, $entry->getSystemProperties()->getArchivedVersion());
        $this->assertInstanceOf(DateTimeImmutable::class, $entry->getSystemProperties()->getArchivedAt());
        $this->assertInstanceOf(Link::class, $entry->getSystemProperties()->getArchivedBy());
        $this->assertTrue($entry->getSystemProperties()->isArchived());

        $entry->unarchive();
        $this->assertNull($entry->getSystemProperties()->getArchivedVersion());
        $this->assertFalse($entry->getSystemProperties()->isArchived());

        $entry->publish();
        $this->assertSame(4, $entry->getSystemProperties()->getPublishedVersion());
        $this->assertTrue($entry->getSystemProperties()->isPublished());

        $entry->setName('en-US', 'An even better name');
        $entry->update();
        $this->assertTrue($entry->getSystemProperties()->isPublished());
        $this->assertTrue($entry->getSystemProperties()->isUpdated());

        $entry->unpublish();
        $this->assertNull($entry->getSystemProperties()->getPublishedVersion());
        $this->assertFalse($entry->getSystemProperties()->isPublished());

        $entry->delete();
    }

    /**
     * @vcr e2e_entry_create_with_id.json
     */
    public function testCreateWithGivenId()
    {
        $proxy = $this->getReadWriteEnvironmentProxy();

        $entry = (new Entry('testCt'))
            ->setField('name', 'en-US', 'A name')
        ;

        $proxy->create($entry, 'myCustomTestEntry');
        $this->assertSame('myCustomTestEntry', $entry->getId());

        $entry->delete();
    }

    /**
     * @vcr e2e_entry_get_without_fields.json
     */
    public function testGetEntryWithoutFields()
    {
        $proxy = $this->getReadOnlyEnvironmentProxy();

        // This entry has nothing in its `fields` property,
        // and because of this, Contentful omits the property altogether.
        // Without a default value in the ResourceBuilder, this call would cause a
        // "Undefined index: fields" error message
        $proxy->getEntry('2cOd0Aho3WkowMgk2C02iy');

        $this->markTestAsPassed();
    }

    /**
     * @vcr e2e_entry_edit_with_rich_text.json
     */
    public function testEditWithRichText()
    {
        $proxy = $this->getReadWriteEnvironmentProxy();

        $entry = new Entry('richTextContentType');
        $entry->setField('title', 'en-US', 'Some title');

        $bodyValue = [
            'data' => [],
            'content' => [
                [
                    'data' => [],
                    'content' => [
                        [
                            'data' => [],
                            'marks' => [],
                            'value' => 'Some title',
                            'nodeType' => 'text',
                        ],
                    ],
                    'nodeType' => 'heading-1',
                ],
                [
                    'data' => [],
                    'content' => [],
                    'nodeType' => 'hr',
                ],
                [
                    'nodeType' => 'unordered-list',
                    'content' => [
                        [
                            'nodeType' => 'list-item',
                            'data' => [],
                            'content' => [
                                [
                                    'nodeType' => 'paragraph',
                                    'content' => [
                                        [
                                            'nodeType' => 'text',
                                            'value' => 'First',
                                            'marks' => [
                                                ['type' => 'underline'],
                                            ],
                                            'data' => [],
                                        ],
                                        [
                                            'nodeType' => 'text',
                                            'value' => ' element',
                                            'marks' => [],
                                            'data' => [],
                                        ],
                                    ],
                                    'data' => [],
                                ],
                            ],
                        ],
                        [
                            'nodeType' => 'list-item',
                            'content' => [
                                [
                                    'nodeType' => 'paragraph',
                                    'content' => [
                                        [
                                            'nodeType' => 'text',
                                            'value' => 'Second',
                                            'marks' => [
                                                ['type' => 'bold'],
                                            ],
                                            'data' => [],
                                        ],
                                        [
                                            'nodeType' => 'text',
                                            'value' => ' element',
                                            'marks' => [],
                                            'data' => [],
                                        ],
                                    ],
                                    'data' => [],
                                ],
                            ],
                            'data' => [],
                        ],
                        [
                            'nodeType' => 'list-item',
                            'content' => [
                                [
                                    'nodeType' => 'paragraph',
                                    'content' => [
                                        [
                                            'nodeType' => 'text',
                                            'value' => 'Third',
                                            'marks' => [
                                                ['type' => 'italic'],
                                            ],
                                            'data' => [],
                                        ],
                                        [
                                            'nodeType' => 'text',
                                            'value' => ' element',
                                            'marks' => [],
                                            'data' => [],
                                        ],
                                    ],
                                    'data' => [],
                                ],
                            ],
                            'data' => [],
                        ],
                    ],
                    'data' => [],
                ],
                [
                    'nodeType' => 'paragraph',
                    'content' => [
                        [
                            'nodeType' => 'text',
                            'data' => [],
                            'value' => 'Hello, is it me you\'re looking for?',
                            'marks' => [
                                ['type' => 'code'],
                            ],
                        ],
                    ],
                    'data' => [],
                ],
                [
                    'nodeType' => 'paragraph',
                    'content' => [
                        [
                            'nodeType' => 'text',
                            'value' => '',
                            'marks' => [],
                            'data' => [],
                        ],
                        [
                            'nodeType' => 'hyperlink',
                            'content' => [
                                [
                                    'nodeType' => 'text',
                                    'value' => 'Contentful',
                                    'marks' => [],
                                    'data' => [],
                                ],
                            ],
                            'data' => [
                                'uri' => 'https://www.contentful.com',
                            ],
                        ],
                        [
                            'nodeType' => 'text',
                            'value' => ' is great.',
                            'marks' => [],
                            'data' => [],
                        ],
                    ],
                    'data' => [],
                ],
            ],
            'nodeType' => 'document',
        ];
        $entry->setField('body', 'en-US', $bodyValue);

        $proxy->create($entry);

        $entry->setField('title', 'en-US', 'Another title');
        $entry->update();

        $entry->delete();

        $this->markTestAsPassed();
    }
}
