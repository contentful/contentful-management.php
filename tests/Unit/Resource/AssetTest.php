<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Resource;

use Contentful\File\RemoteUploadFile;
use Contentful\Management\Resource\Asset;
use PHPUnit\Framework\TestCase;

class AssetTest extends TestCase
{
    public function testGetSetData()
    {
        $asset = new Asset();

        $this->assertEquals('Asset', $asset->getSystemProperties()->getType());

        $asset->setTitle('en-US', 'A cool asset');
        $this->assertEquals('A cool asset', $asset->getTitle('en-US'));
        $this->assertNull($asset->getTitle('de-DE'));

        $asset->setDescription('en-US', 'This asset is really cool');
        $this->assertEquals('This asset is really cool', $asset->getDescription('en-US'));
        $this->assertNull($asset->getDescription('de-DE'));

        $file = new RemoteUploadFile('testfile.jpg', 'image/jpeg', 'https://www.example.com/testfile.jpeg');
        $asset->setFile('en-US', $file);
        $this->assertSame($file, $asset->getFile('en-US'));
        $this->assertNull($asset->getFile('de-DE'));
    }

    public function testJsonSerialize()
    {
        $file = new RemoteUploadFile('testfile.jpg', 'image/jpeg', 'https://www.example.com/testfile.jpeg');
        $asset = (new Asset())
            ->setTitle('en-US', 'A cool asset')
            ->setDescription('en-US', 'This asset is really cool')
            ->setFile('en-US', $file);

        $this->assertJsonStringEqualsJsonString('{"fields":{"file":{"en-US":{"fileName":"testfile.jpg","contentType":"image/jpeg","upload":"https://www.example.com/testfile.jpeg"}},"title":{"en-US":"A cool asset"},"description":{"en-US":"This asset is really cool"}},"sys":{"type":"Asset"}}', json_encode($asset));
    }
}
