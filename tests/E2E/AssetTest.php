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
use Contentful\Core\File\File;
use Contentful\Core\File\RemoteUploadFile;
use Contentful\Core\File\UnprocessedFileInterface;
use Contentful\Management\Query;
use Contentful\Management\Resource\Asset;
use Contentful\Management\Resource\Upload;
use Contentful\Tests\Management\BaseTestCase;
use GuzzleHttp\Psr7\Utils;

class AssetTest extends BaseTestCase
{
    /**
     * @vcr e2e_asset_get_one.json
     */
    public function testGetOne()
    {
        $proxy = $this->getReadOnlyEnvironmentProxy();

        $asset = $proxy->getAsset('2TEG7c2zYkSSuKmsqEwCS');
        $this->assertSame('Contentful Logo', $asset->getTitle('en-US'));
        $this->assertNull($asset->getDescription('en-US'));
        $this->assertLink('2TEG7c2zYkSSuKmsqEwCS', 'Asset', $asset->asLink());
        $this->assertSame(
            '//images.ctfassets.net/34luz0flcmxt/2TEG7c2zYkSSuKmsqEwCS/22da0779cac76ba6b74b5c2cbf084b97/contentful-logo-C395C545BF-seeklogo.com.png',
            $asset->getFile('en-US')->getUrl()
        );

        $sys = $asset->getSystemProperties();
        $this->assertSame('2TEG7c2zYkSSuKmsqEwCS', $sys->getId());
        $this->assertSame('Asset', $sys->getType());
        $this->assertSame(11, $sys->getVersion());
        $this->assertLink($this->readOnlySpaceId, 'Space', $sys->getSpace());
        $this->assertSame('2017-08-22T15:41:45.127Z', (string) $sys->getCreatedAt());
        $this->assertSame('2017-08-22T15:42:20.969Z', (string) $sys->getUpdatedAt());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getCreatedBy());
        $this->assertLink('1CECdY5ZhqJapGieg6QS9P', 'User', $sys->getUpdatedBy());
        $this->assertSame(10, $sys->getPublishedVersion());
        $this->assertSame(1, $sys->getPublishedCounter());
        $this->assertSame('2017-08-22T15:42:20.946Z', (string) $sys->getPublishedAt());
        $this->assertSame('2017-08-22T15:42:20.946Z', (string) $sys->getFirstPublishedAt());
    }

    /**
     * @vcr e2e_asset_get_one_from_space_proxy.json
     */
    public function testGetOneFromSpaceProxy()
    {
        $proxy = $this->getReadOnlySpaceProxy();

        $asset = $proxy->getAsset('master', '2TEG7c2zYkSSuKmsqEwCS');
        $this->assertSame('Contentful Logo', $asset->getTitle('en-US'));
        $this->assertNull($asset->getDescription('en-US'));
        $this->assertLink('2TEG7c2zYkSSuKmsqEwCS', 'Asset', $asset->asLink());
        $this->assertSame(
            '//images.ctfassets.net/34luz0flcmxt/2TEG7c2zYkSSuKmsqEwCS/22da0779cac76ba6b74b5c2cbf084b97/contentful-logo-C395C545BF-seeklogo.com.png',
            $asset->getFile('en-US')->getUrl()
        );
    }

    /**
     * @vcr e2e_asset_get_collection.json
     */
    public function testGetCollection()
    {
        $proxy = $this->getReadOnlyEnvironmentProxy();
        $assets = $proxy->getAssets();

        $this->assertInstanceOf(Asset::class, $assets[0]);

        $query = (new Query())
            ->setLimit(1)
        ;
        $assets = $proxy->getAssets($query);
        $this->assertInstanceOf(Asset::class, $assets[0]);
        $this->assertCount(1, $assets);
    }

    /**
     * @vcr e2e_asset_get_collection_from_space_proxy.json
     */
    public function testGetCollectionFromSpaceProxy()
    {
        $proxy = $this->getReadOnlySpaceProxy();
        $assets = $proxy->getAssets('master');

        $this->assertInstanceOf(Asset::class, $assets[0]);

        $query = (new Query())
            ->setLimit(1)
        ;
        $assets = $proxy->getAssets('master', $query);
        $this->assertInstanceOf(Asset::class, $assets[0]);
        $this->assertCount(1, $assets);
    }

    /**
     * @vcr e2e_asset_create_update_process_publish_unpublish_archive_unarchive_delete.json
     */
    public function testCreateUpdateProcessPublishUnpublishArchiveUnarchiveDelete()
    {
        $proxy = $this->getReadWriteEnvironmentProxy();

        $asset = (new Asset())
            ->setTitle('en-US', 'An asset')
            ->setDescription('en-US', 'A really cool asset')
        ;

        $file = new RemoteUploadFile('contentful.svg', 'image/svg+xml', 'https://pbs.twimg.com/profile_images/488880764323250177/CrqV-RjR_normal.jpeg');

        $asset->setFile('en-US', $file);

        $proxy->create($asset);
        $this->assertNotNull($asset->getId());
        $this->assertTrue($asset->getSystemProperties()->isDraft());
        $this->assertFalse($asset->getSystemProperties()->isPublished());
        $this->assertFalse($asset->getSystemProperties()->isUpdated());
        $this->assertFalse($asset->getSystemProperties()->isArchived());

        $asset->process('en-US');

        // Calls the API until the file is processed.
        // Limit is used because repeated requests will be recorded
        // and the same response will be returned
        $query = (new Query())
            ->where('sys.id', $asset->getId())
        ;
        $limit = 0;
        while ($asset->getFile('en-US') instanceof UnprocessedFileInterface) {
            ++$limit;
            $query->setLimit($limit);
            $asset = $proxy->getAssets($query)[0];

            // This is arbitrary
            if ($limit > 50) {
                throw new \RuntimeException('Repeated requests are not yielding a processed file, something is wrong.');
            }
        }

        $asset->setTitle('en-US', 'Even better asset');

        $asset->update();

        $asset->archive();
        $this->assertSame(3, $asset->getSystemProperties()->getArchivedVersion());
        $this->assertTrue($asset->getSystemProperties()->isArchived());

        $asset->unarchive();
        $this->assertNull($asset->getSystemProperties()->getArchivedVersion());
        $this->assertFalse($asset->getSystemProperties()->isArchived());

        $asset->publish();
        $this->assertSame(5, $asset->getSystemProperties()->getPublishedVersion());
        $this->assertTrue($asset->getSystemProperties()->isPublished());

        $asset->unpublish();
        $this->assertNull($asset->getSystemProperties()->getPublishedVersion());
        $this->assertFalse($asset->getSystemProperties()->isPublished());

        $asset->delete();
    }

    /**
     * @vcr e2e_asset_create_with_id.json
     */
    public function testCreateWithId()
    {
        $proxy = $this->getReadWriteEnvironmentProxy();

        $asset = (new Asset())
            ->setTitle('en-US', 'An asset')
            ->setDescription('en-US', 'A really cool asset')
        ;

        $proxy->create($asset, 'myCustomTestAsset');
        $this->assertSame('myCustomTestAsset', $asset->getId());

        $asset->delete();
    }

    /**
     * @vcr e2e_asset_upload.json
     */
    public function testUpload()
    {
        // Uploads are scoped to spaces, but assets are scoped to environments
        $spaceProxy = $this->getReadWriteSpaceProxy();
        $environmentProxy = $spaceProxy->getEnvironmentProxy('master');

        // Creates upload using stream
        $stream = Utils::streamFor(\file_get_contents(__DIR__.'/../Fixtures/E2E/contentful-logo.svg'));
        $streamUpload = new Upload($stream);
        $spaceProxy->create($streamUpload);
        $this->assertNotNull($streamUpload->getId());
        $this->assertInstanceOf(DateTimeImmutable::class, $streamUpload->getSystemProperties()->getExpiresAt());
        $streamUpload->delete();

        // Creates upload using string
        $upload = new Upload(\file_get_contents(__DIR__.'/../Fixtures/E2E/contentful-name.svg'));
        $spaceProxy->create($upload);
        $link = $upload->asLink();
        $this->assertLink($upload->getId(), 'Upload', $link);
        $this->assertNotNull($upload->getId());
        $this->assertInstanceOf(DateTimeImmutable::class, $upload->getSystemProperties()->getExpiresAt());

        $asset = new Asset();
        $asset->setTitle('en-US', 'Contentful');
        $asset->setFile('en-US', $upload->asAssetFile('contentful.svg', 'image/svg+xml'));

        $environmentProxy->create($asset);
        $asset->process('en-US');

        // Calls the API until the file is processed.
        // Limit is used because repeated requests will be recorded
        // and the same response will be returned
        $query = (new Query())
            ->where('sys.id', $asset->getId())
        ;
        $limit = 0;
        while ($asset->getFile('en-US') instanceof UnprocessedFileInterface) {
            ++$limit;
            $query->setLimit($limit);
            $asset = $environmentProxy->getAssets($query)[0];

            // This is arbitrary
            if ($limit > 50) {
                throw new \RuntimeException('Repeated requests are not yielding a processed file, something is wrong.');
            }
        }

        $this->assertSame('contentful.svg', $asset->getFile('en-US')->getFileName());
        $this->assertSame('image/svg+xml', $asset->getFile('en-US')->getContentType());
        $this->assertStringContainsStringIgnoringCase('ctfassets.net', $asset->getFile('en-US')->getUrl());

        $upload = $spaceProxy->getUpload($upload->getId());
        $this->assertLink($upload->getId(), 'Upload', $upload->asLink());

        $upload->delete();
        $asset->delete();
    }

    /**
     * @vcr e2e_asset_text_file.json
     */
    public function testTextFile()
    {
        $proxy = $this->getReadOnlyEnvironmentProxy();

        $asset = $proxy->getAsset('1Gdj0yMYb60MuI6OCSkqMu');

        $this->assertSame('MIT', $asset->getTitle('en-US'));
        $this->assertSame('This is the MIT license', $asset->getDescription('en-US'));
        $file = $asset->getFile('en-US');
        $this->assertInstanceOf(File::class, $file);
        $this->assertSame('MIT.md', $file->getFileName());
        $this->assertSame('text/plain', $file->getContentType());
        $this->assertSame('//assets.ctfassets.net/34luz0flcmxt/1Gdj0yMYb60MuI6OCSkqMu/4211a1a1c41d6e477e4f8bcaea8b68ab/MIT.md', $file->getUrl());
        $this->assertSame(1065, $file->getSize());
    }
}
