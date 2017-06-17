<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\E2E;

use Contentful\File\UploadFile;
use Contentful\Link;
use Contentful\Management\Asset;
use Contentful\Management\Query;
use Contentful\Tests\End2EndTestCase;

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

        $json = '{"fields":{"title":{"en-US":"Nyan Cat"},"file":{"en-US":{"fileName":"Nyan_cat_250px_frame.png","contentType":"image\/png","details":{"image":{"width": 250,"height": 250},"size": 12273},"url":"\/\/images.contentful.com\/cfexampleapi\/4gp6taAwW4CmSgumq2ekUm\/9da0cd1936871b8d72343e895a00d611\/Nyan_cat_250px_frame.png"}}},"sys":{"id": "nyancat","type": "Asset","space":{"sys":{"type":"Link","linkType": "Space", "id":"cfexampleapi"}},"createdAt":"2013-09-02T14:54:17.868Z","createdBy":{"sys":{"type": "Link","linkType":"User","id": "7BslKh9TdKGOK41VmLDjFZ"}},"firstPublishedAt": "2013-09-02T14:56:34.240Z","publishedCounter": 1,"publishedAt":"2013-09-02T14:56:34.240Z","publishedBy":{"sys":{"type": "Link","linkType": "User","id":"7BslKh9TdKGOK41VmLDjFZ"}},"publishedVersion": 1,"version": 2,"updatedAt":"2013-09-02T14:56:34.264Z","updatedBy":{"sys":{"type":"Link","linkType": "User","id": "7BslKh9TdKGOK41VmLDjFZ"}}}}';
        $this->assertJsonStringEqualsJsonString($json, json_encode($asset));
    }

    /**
     * @vcr e2e_asset_get_collection.json
     */
    public function testGetAssets()
    {
        $manager = $this->getReadOnlySpaceManager();
        $assets = $manager->getAssets();

        $this->assertInstanceOf(Asset::class, $assets[0]);

        $query = (new Query)
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
            ->setTitle('An asset', 'en-US')
            ->setDescription('A really cool asset', 'en-US');

        $file = new UploadFile('contentful.svg', 'image/svg+xml', 'https://pbs.twimg.com/profile_images/488880764323250177/CrqV-RjR_normal.jpeg');

        $asset->setFile($file, 'en-US');

        $manager->create($asset);
        $this->assertNotNull($asset->getSystemProperties()->getId());

        $manager->processAsset($asset, 'en-US');
        $id = $asset->getSystemProperties()->getId();

        // Poll the API until processing is complete
        while ($asset->getFile('en-US') instanceof UploadFile) {
            sleep(1);
            $asset = $manager->getAsset($id);
        }

        $asset->setTitle('Even better asset', 'en-US');

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
            ->setTitle('An asset', 'en-US')
            ->setDescription('A really cool asset', 'en-US');

        $manager->create($asset, 'myCustomTestAsset');
        $this->assertEquals('myCustomTestAsset', $asset->getSystemProperties()->getId());

        $manager->delete($asset);
    }
}
