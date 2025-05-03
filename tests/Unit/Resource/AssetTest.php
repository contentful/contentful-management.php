<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource;

use Contentful\Core\File\RemoteUploadFile;
use Contentful\Management\Resource\Asset;
use Contentful\Tests\Management\BaseTestCase;

class AssetTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $asset = new Asset();

        $asset->setTitle('en-US', 'A cool asset');
        $this->assertSame('A cool asset', $asset->getTitle('en-US'));
        $this->assertNull($asset->getTitle('it-IT'));
        $this->assertSame(['en-US' => 'A cool asset'], $asset->getTitles());
        $asset->setTitle('en-US', null);
        $this->assertSame([], $asset->getTitles());
        $this->assertNull($asset->getTitle('en-US'));

        $asset->setDescription('en-US', 'This asset is really cool');
        $this->assertSame('This asset is really cool', $asset->getDescription('en-US'));
        $this->assertNull($asset->getDescription('it-IT'));
        $this->assertSame(['en-US' => 'This asset is really cool'], $asset->getDescriptions());
        $asset->setDescription('en-US', null);
        $this->assertSame([], $asset->getDescriptions());
        $this->assertNull($asset->getDescription('en-US'));

        $file = new RemoteUploadFile('testfile.jpg', 'image/jpeg', 'https://www.example.com/testfile.jpeg');
        $asset->setFile('en-US', $file);
        $this->assertSame($file, $asset->getFile('en-US'));
        $this->assertNull($asset->getFile('it-IT'));
        $this->assertSame(['en-US' => $file], $asset->getFiles());
        $asset->setFile('en-US', null);
        $this->assertSame([], $asset->getFiles());
        $this->assertNull($asset->getFile('en-US'));
    }

    public function testJsonSerialize()
    {
        $file = new RemoteUploadFile('testfile.jpg', 'image/jpeg', 'https://www.example.com/testfile.jpeg');
        $asset = (new Asset())
            ->setTitle('en-US', 'A cool asset')
            ->setDescription('en-US', 'This asset is really cool')
            ->setFile('en-US', $file)
        ;

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/asset.json', $asset);
    }
}
