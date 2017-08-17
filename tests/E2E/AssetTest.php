<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\E2E;

use Contentful\File\LocalUploadFile;
use Contentful\File\RemoteUploadFile;
use Contentful\Link;
use Contentful\Management\Query;
use Contentful\Management\Resource\Asset;
use Contentful\Management\Resource\Upload;
use Contentful\Tests\End2EndTestCase;
use function GuzzleHttp\Psr7\stream_for;

class AssetTest extends End2EndTestCase
{
    /**
     * @vcr e2e_asset_get.json
     */
    public function testGetAsset()
    {
        $manager = $this->getReadOnlySpaceManager();

        $asset = $manager->getAsset('nyancat');
        $this->assertEquals('Nyan Cat', $asset->getTitle('en-US'));
        $this->assertNull($asset->getTitle('tlh'));
        $this->assertNull($asset->getDescription('en-US'));
        $this->assertEquals(new Link('nyancat', 'Asset'), $asset->asLink());

        $sys = $asset->getSystemProperties();
        $this->assertEquals('nyancat', $sys->getId());
        $this->assertEquals('Asset', $sys->getType());
        $this->assertEquals(2, $sys->getVersion());
        $this->assertEquals(new Link($this->readOnlySpaceId, 'Space'), $sys->getSpace());
        $this->assertEquals(new \DateTimeImmutable('2013-09-02T14:54:17.868'), $sys->getCreatedAt());
        $this->assertEquals(new \DateTimeImmutable('2013-09-02T14:56:34.264'), $sys->getUpdatedAt());
        $this->assertEquals(new Link('7BslKh9TdKGOK41VmLDjFZ', 'User'), $sys->getCreatedBy());
        $this->assertEquals(new Link('7BslKh9TdKGOK41VmLDjFZ', 'User'), $sys->getUpdatedBy());
        $this->assertEquals(1, $sys->getPublishedVersion());
        $this->assertEquals(1, $sys->getPublishedCounter());
        $this->assertEquals(new \DateTimeImmutable('2013-09-02T14:56:34.24'), $sys->getPublishedAt());
        $this->assertEquals(new \DateTimeImmutable('2013-09-02T14:56:34.24'), $sys->getFirstPublishedAt());
    }

    /**
     * @vcr e2e_asset_get_collection.json
     */
    public function testGetAssets()
    {
        $manager = $this->getReadOnlySpaceManager();
        $assets = $manager->getAssets();

        $this->assertInstanceOf(Asset::class, $assets[0]);

        $query = (new Query())
            ->setLimit(1);
        $assets = $manager->getAssets($query);
        $this->assertInstanceOf(Asset::class, $assets[0]);
        $this->assertCount(1, $assets);
    }

    /**
     * @vcr e2e_asset_create_update_process_publish_unpublish_archive_unarchive_delete.json
     */
    public function testCreateUpdateProcessPublishUnpublishArchiveUnarchiveDelete()
    {
        $manager = $this->getReadWriteSpaceManager();

        $asset = (new Asset())
            ->setTitle('en-US', 'An asset')
            ->setDescription('en-US', 'A really cool asset');

        $file = new RemoteUploadFile('contentful.svg', 'image/svg+xml', 'https://pbs.twimg.com/profile_images/488880764323250177/CrqV-RjR_normal.jpeg');

        $asset->setFile('en-US', $file);

        $manager->create($asset);
        $this->assertNotNull($asset->getSystemProperties()->getId());

        $manager->processAsset($asset, 'en-US');

        // Calls the API until the file is processed.
        // Limit is used because repeated requests will be recorded
        // and the same response will be returned
        $query = (new Query())
            ->where('sys.id', $asset->getSystemProperties()->getId())
        ;
        $limit = 0;
        while ($asset->getFile('en-US') instanceof RemoteUploadFile) {
            ++$limit;
            $query->setLimit($limit);
            $asset = $manager->getAssets($query)[0];

            // This is arbitrary
            if ($limit > 50) {
                throw new \RuntimeException('Repeated requests are not yielding a processed file, something is wrong');
            }
        }

        $asset->setTitle('en-US', 'Even better asset');

        $manager->update($asset);

        $manager->archive($asset);
        $this->assertEquals(3, $asset->getSystemProperties()->getArchivedVersion());

        $manager->unarchive($asset);
        $this->assertNull($asset->getSystemProperties()->getArchivedVersion());

        $manager->publish($asset);
        $this->assertEquals(5, $asset->getSystemProperties()->getPublishedVersion());

        $manager->unpublish($asset);
        $this->assertNull($asset->getSystemProperties()->getPublishedVersion());

        $manager->delete($asset);
    }

    /**
     * @vcr e2e_asset_create_with_id.json
     */
    public function testCreateAssetWithGivenId()
    {
        $manager = $this->getReadWriteSpaceManager();

        $asset = (new Asset())
            ->setTitle('en-US', 'An asset')
            ->setDescription('en-US', 'A really cool asset');

        $manager->create($asset, 'myCustomTestAsset');
        $this->assertEquals('myCustomTestAsset', $asset->getSystemProperties()->getId());

        $manager->delete($asset);
    }

    /**
     * @vcr e2e_asset_upload.json
     */
    public function testUploadAsset()
    {
        $manager = $this->getReadWriteSpaceManager();

        // Creates upload using fopen
        $fopenUpload = new Upload(fopen(__DIR__.'/../fixtures/contentful-lab.svg', 'r'));
        $manager->create($fopenUpload);
        $this->assertNotNull($fopenUpload->getSystemProperties()->getId());
        $this->assertInstanceOf(\DateTimeImmutable::class, $fopenUpload->getSystemProperties()->getExpiresAt());
        $manager->delete($fopenUpload);

        // Creates upload using stream
        $stream = stream_for(file_get_contents(__DIR__.'/../fixtures/contentful-logo.svg'));
        $streamUpload = new Upload($stream);
        $manager->create($streamUpload);
        $this->assertNotNull($streamUpload->getSystemProperties()->getId());
        $this->assertInstanceOf(\DateTimeImmutable::class, $streamUpload->getSystemProperties()->getExpiresAt());
        $manager->delete($streamUpload);

        // Creates upload using string
        $upload = new Upload(file_get_contents(__DIR__.'/../fixtures/contentful-name.svg'));
        $manager->create($upload);
        $this->assertEquals(new Link($upload->getSystemProperties()->getId(), 'Upload'), $upload->asLink());
        $this->assertNotNull($upload->getSystemProperties()->getId());
        $this->assertInstanceOf(\DateTimeImmutable::class, $upload->getSystemProperties()->getExpiresAt());

        $uploadFromFile = new LocalUploadFile('contentful.svg', 'image/svg+xml', $upload->asLink());

        $asset = new Asset();
        $asset->setTitle('en-US', 'Contentful');
        $asset->setFile('en-US', $uploadFromFile);

        $manager->create($asset);
        $manager->processAsset($asset, 'en-US');

        // Calls the API until the file is processed.
        // Limit is used because repeated requests will be recorded
        // and the same response will be returned
        $query = (new Query())
            ->where('sys.id', $asset->getSystemProperties()->getId())
        ;
        $limit = 0;
        while ($asset->getFile('en-US') instanceof LocalUploadFile) {
            ++$limit;
            $query->setLimit($limit);
            $asset = $manager->getAssets($query)[0];

            // This is arbitrary
            if ($limit > 50) {
                throw new \RuntimeException('Repeated requests are not yielding a processed file, something is wrong');
            }
        }

        $this->assertEquals('contentful.svg', $asset->getFile('en-US')->getFileName());
        $this->assertEquals('image/svg+xml', $asset->getFile('en-US')->getContentType());
        $this->assertContains('contentful.com', $asset->getFile('en-US')->getUrl());

        $upload = $manager->getUpload($upload->getSystemProperties()->getId());

        $manager->delete($upload);
        $manager->delete($asset);
    }
}
