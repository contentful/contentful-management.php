<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\E2E;

use Contentful\Link;
use Contentful\Management\Client;
use Contentful\Management\Entry;

class EntryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $this->token = getenv('CONTENTFUL_CMA_TEST_TOKEN');
        $this->client = new Client($this->token);
    }

    /**
     * @vcr e2e_entry_get.json
     */
    public function testGetEntry()
    {
        $manager = $this->client->getSpaceManager('cfexampleapi');

        $entry = $manager->getEntry('nyancat');

        $this->assertEquals('Nyan Cat', $entry->getField('name', 'en-US'));
        $this->assertEquals('Nyan vIghro\'', $entry->getField('name', 'tlh'));

        $sys = $entry->getSystemProperties();
        $this->assertEquals('nyancat', $sys->getId());
        $this->assertEquals('Entry', $sys->getType());
        $this->assertEquals(new Link('cat', 'ContentType'), $sys->getContentType());
        $this->assertEquals(15, $sys->getVersion());
        $this->assertEquals(new Link('cfexampleapi', 'Space'), $sys->getSpace());
        $this->assertEquals(new \DateTimeImmutable('2013-06-27T22:46:15.91'), $sys->getCreatedAt());
        $this->assertEquals(new \DateTimeImmutable('2016-05-19T11:40:57.752'), $sys->getUpdatedAt());
        $this->assertEquals(new Link('7BslKh9TdKGOK41VmLDjFZ', 'User'), $sys->getCreatedBy());
        $this->assertEquals(new Link('5NItczv8FWvPn5UTJpTOMM', 'User'), $sys->getUpdatedBy());
        $this->assertEquals(10, $sys->getPublishedVersion());
        $this->assertEquals(5, $sys->getPublishedCounter());
        $this->assertEquals(new \DateTimeImmutable('2013-09-04T09:19:39.027'), $sys->getPublishedAt());
        $this->assertEquals(new \DateTimeImmutable('2013-06-27T22:46:19.513'), $sys->getFirstPublishedAt());
    }

    /**
     * @vcr e2e_entry_create_update_publish_unpublish_archive_unarchive_delete.json
     */
    public function testCreateUpdatePublishUnpublishArchiveUnarchiveDelete()
    {
        $manager = $this->client->getSpaceManager('34luz0flcmxt');

        $entry = (new Entry('testCt'))
            ->setField('name', 'A name', 'en-US');

        $manager->create($entry);
        $this->assertNotNull($entry->getSystemProperties()->getId());

        $entry->setField('name', 'A better name', 'en-US');

        $manager->update($entry);

        $manager->archive($entry);
        $this->assertEquals(2, $entry->getSystemProperties()->getArchivedVersion());

        $manager->unarchive($entry);
        $this->assertNull($entry->getSystemProperties()->getArchivedVersion());

        $manager->publish($entry);
        $this->assertEquals(4, $entry->getSystemProperties()->getPublishedVersion());

        $manager->unpublish($entry);
        $this->assertNull($entry->getSystemProperties()->getPublishedVersion());

        $manager->delete($entry);
    }
}
