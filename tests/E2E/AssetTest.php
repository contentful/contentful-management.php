<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\E2E\Management;

use Contentful\File\File;
use Contentful\File\LocalUploadFile;
use Contentful\File\RemoteUploadFile;
use Contentful\Link;
use Contentful\Management\ApiDateTime;
use Contentful\Management\Query;
use Contentful\Management\Resource\Asset;
use Contentful\Management\Resource\Upload;
use Contentful\Tests\End2EndTestCase;
use function GuzzleHttp\Psr7\stream_for;

class AssetTest extends End2EndTestCase
{
    /**
     * @vcr e2e_asset_get_one.json
     */
    public function testGetAsset()
    {
        $client = $this->getReadOnlyClient();

        $asset = $client->asset->get('nyancat');
        $this->assertEquals('Nyan Cat', $asset->getTitle('en-US'));
        $this->assertNull($asset->getTitle('tlh'));
        $this->assertNull($asset->getDescription('en-US'));
        $this->assertEquals(new Link('nyancat', 'Asset'), $asset->asLink());

        $sys = $asset->getSystemProperties();
        $this->assertEquals('nyancat', $sys->getId());
        $this->assertEquals('Asset', $sys->getType());
        $this->assertEquals(2, $sys->getVersion());
        $this->assertEquals(new Link($this->readOnlySpaceId, 'Space'), $sys->getSpace());
        $this->assertEquals(new ApiDateTime('2013-09-02T14:54:17.868'), $sys->getCreatedAt());
        $this->assertEquals(new ApiDateTime('2013-09-02T14:56:34.264'), $sys->getUpdatedAt());
        $this->assertEquals(new Link('7BslKh9TdKGOK41VmLDjFZ', 'User'), $sys->getCreatedBy());
        $this->assertEquals(new Link('7BslKh9TdKGOK41VmLDjFZ', 'User'), $sys->getUpdatedBy());
        $this->assertEquals(1, $sys->getPublishedVersion());
        $this->assertEquals(1, $sys->getPublishedCounter());
        $this->assertEquals(new ApiDateTime('2013-09-02T14:56:34.24'), $sys->getPublishedAt());
        $this->assertEquals(new ApiDateTime('2013-09-02T14:56:34.24'), $sys->getFirstPublishedAt());
    }

    /**
     * @vcr e2e_asset_get_collection.json
     */
    public function testGetAssets()
    {
        $client = $this->getReadOnlyClient();
        $assets = $client->asset->getAll();

        $this->assertInstanceOf(Asset::class, $assets[0]);

        $query = (new Query())
            ->setLimit(1);
        $assets = $client->asset->getAll($query);
        $this->assertInstanceOf(Asset::class, $assets[0]);
        $this->assertCount(1, $assets);
    }

    /**
     * @vcr e2e_asset_create_update_process_publish_unpublish_archive_unarchive_delete.json
     */
    public function testCreateUpdateProcessPublishUnpublishArchiveUnarchiveDelete()
    {
        $client = $this->getReadWriteClient();

        $asset = (new Asset())
            ->setTitle('en-US', 'An asset')
            ->setDescription('en-US', 'A really cool asset');

        $file = new RemoteUploadFile('contentful.svg', 'image/svg+xml', 'https://pbs.twimg.com/profile_images/488880764323250177/CrqV-RjR_normal.jpeg');

        $asset->setFile('en-US', $file);

        $client->asset->create($asset);
        $this->assertNotNull($asset->getId());

        $asset->process('en-US');

        // Calls the API until the file is processed.
        // Limit is used because repeated requests will be recorded
        // and the same response will be returned
        $query = (new Query())
            ->where('sys.id', $asset->getId())
        ;
        $limit = 0;
        while ($asset->getFile('en-US') instanceof RemoteUploadFile) {
            ++$limit;
            $query->setLimit($limit);
            $asset = $client->asset->getAll($query)[0];

            // This is arbitrary
            if ($limit > 50) {
                throw new \RuntimeException(
                    'Repeated requests are not yielding a processed file, something is wrong.'
                );
            }
        }

        $asset->setTitle('en-US', 'Even better asset');

        $asset->update();

        $asset->archive();
        $this->assertEquals(3, $asset->getSystemProperties()->getArchivedVersion());

        $asset->unarchive();
        $this->assertNull($asset->getSystemProperties()->getArchivedVersion());

        $asset->publish();
        $this->assertEquals(5, $asset->getSystemProperties()->getPublishedVersion());

        $asset->unpublish();
        $this->assertNull($asset->getSystemProperties()->getPublishedVersion());

        $asset->delete();
    }

    /**
     * @vcr e2e_asset_create_with_id.json
     */
    public function testCreateAssetWithGivenId()
    {
        $client = $this->getReadWriteClient();

        $asset = (new Asset())
            ->setTitle('en-US', 'An asset')
            ->setDescription('en-US', 'A really cool asset');

        $client->asset->create($asset, 'myCustomTestAsset');
        $this->assertEquals('myCustomTestAsset', $asset->getId());

        $asset->delete();
    }

    /**
     * @vcr e2e_asset_upload.json
     */
    public function testUploadAsset()
    {
        $client = $this->getReadWriteClient();

        // Creates upload using fopen
        $fopenUpload = new Upload(fopen(__DIR__.'/../fixtures/contentful-lab.svg', 'r'));
        $client->upload->create($fopenUpload);
        $this->assertNotNull($fopenUpload->getId());
        $this->assertInstanceOf(ApiDateTime::class, $fopenUpload->getSystemProperties()->getExpiresAt());
        $fopenUpload->delete();

        // Creates upload using stream
        $stream = stream_for(file_get_contents(__DIR__.'/../fixtures/contentful-logo.svg'));
        $streamUpload = new Upload($stream);
        $client->upload->create($streamUpload);
        $this->assertNotNull($streamUpload->getId());
        $this->assertInstanceOf(ApiDateTime::class, $streamUpload->getSystemProperties()->getExpiresAt());
        $streamUpload->delete();

        // Creates upload using string
        $upload = new Upload(file_get_contents(__DIR__.'/../fixtures/contentful-name.svg'));
        $client->upload->create($upload);
        $this->assertEquals(new Link($upload->getId(), 'Upload'), $upload->asLink());
        $this->assertNotNull($upload->getId());
        $this->assertInstanceOf(ApiDateTime::class, $upload->getSystemProperties()->getExpiresAt());

        $uploadFromFile = new LocalUploadFile('contentful.svg', 'image/svg+xml', $upload->asLink());

        $asset = new Asset();
        $asset->setTitle('en-US', 'Contentful');
        $asset->setFile('en-US', $uploadFromFile);

        $client->asset->create($asset);
        $asset->process('en-US');

        // Calls the API until the file is processed.
        // Limit is used because repeated requests will be recorded
        // and the same response will be returned
        $query = (new Query())
            ->where('sys.id', $asset->getId())
        ;
        $limit = 0;
        while ($asset->getFile('en-US') instanceof LocalUploadFile) {
            ++$limit;
            $query->setLimit($limit);
            $asset = $client->asset->getAll($query)[0];

            // This is arbitrary
            if ($limit > 50) {
                throw new \RuntimeException(
                    'Repeated requests are not yielding a processed file, something is wrong.'
                );
            }
        }

        $this->assertEquals('contentful.svg', $asset->getFile('en-US')->getFileName());
        $this->assertEquals('image/svg+xml', $asset->getFile('en-US')->getContentType());
        $this->assertContains('contentful.com', $asset->getFile('en-US')->getUrl());

        $upload = $client->resolveLink($upload->asLink());

        $upload->delete();
        $asset->delete();
    }

    /**
     * @vcr e2e_asset_text_file.json
     */
    public function testTextFileAsset()
    {
        $client = $this->getReadWriteClient();

        $asset = $client->asset->get('1Gdj0yMYb60MuI6OCSkqMu');

        $this->assertSame('MIT', $asset->getTitle('en-US'));
        $this->assertSame('This is the MIT license', $asset->getDescription('en-US'));
        $file = $asset->getFile('en-US');
        $this->assertInstanceOf(File::class, $file);
        $this->assertSame('MIT.md', $file->getFileName());
        $this->assertSame('text/plain', $file->getContentType());
        $this->assertSame('//assets.contentful.com/34luz0flcmxt/1Gdj0yMYb60MuI6OCSkqMu/4211a1a1c41d6e477e4f8bcaea8b68ab/MIT.md', $file->getUrl());
        $this->assertSame(1065, $file->getSize());
    }
}
