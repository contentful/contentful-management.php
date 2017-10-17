<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource;

use Contentful\File\RemoteUploadFile;
use Contentful\Management\Resource\Asset;
use Contentful\Tests\Management\BaseTestCase;

class AssetTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $asset = new Asset();

        $this->assertEquals('Asset', $asset->getSystemProperties()->getType());

        $asset->setTitle('en-US', 'A cool asset');
        $this->assertEquals('A cool asset', $asset->getTitle('en-US'));
        $this->assertNull($asset->getTitle('it-IT'));
        $this->assertEquals(['en-US' => 'A cool asset'], $asset->getTitles());

        $asset->setDescription('en-US', 'This asset is really cool');
        $this->assertEquals('This asset is really cool', $asset->getDescription('en-US'));
        $this->assertNull($asset->getDescription('it-IT'));
        $this->assertEquals(['en-US' => 'This asset is really cool'], $asset->getDescriptions());

        $file = new RemoteUploadFile('testfile.jpg', 'image/jpeg', 'https://www.example.com/testfile.jpeg');
        $asset->setFile('en-US', $file);
        $this->assertSame($file, $asset->getFile('en-US'));
        $this->assertNull($asset->getFile('it-IT'));
        $this->assertEquals(['en-US' => $file], $asset->getFiles());
    }

    public function testJsonSerialize()
    {
        $file = new RemoteUploadFile('testfile.jpg', 'image/jpeg', 'https://www.example.com/testfile.jpeg');
        $asset = (new Asset())
            ->setTitle('en-US', 'A cool asset')
            ->setDescription('en-US', 'This asset is really cool')
            ->setFile('en-US', $file);

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/asset.json', $asset);
    }
}
