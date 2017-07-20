<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\Unit\Resource;

use Contentful\File\RemoteUploadFile;
use Contentful\Management\Resource\Asset;

class AssetTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetData()
    {
        $asset = new Asset;

        $this->assertEquals('Asset', $asset->getSystemProperties()->getType());

        $asset->setTitle('A cool asset', 'en-US');
        $this->assertEquals('A cool asset', $asset->getTitle('en-US'));
        $this->assertNull($asset->getTitle('de-DE'));

        $asset->setDescription('This asset is really cool', 'en-US');
        $this->assertEquals('This asset is really cool', $asset->getDescription('en-US'));
        $this->assertNull($asset->getDescription('de-DE'));

        $file = new RemoteUploadFile('testfile.jpg', 'image/jpeg', 'https://www.example.com/testfile.jpeg');
        $asset->setFile($file, 'en-US');
        $this->assertSame($file, $asset->getFile('en-US'));
        $this->assertNull($asset->getFile('de-DE'));
    }

    public function testJsonSerialize()
    {
        $file = new RemoteUploadFile('testfile.jpg', 'image/jpeg', 'https://www.example.com/testfile.jpeg');
        $asset = (new Asset)
            ->setTitle('A cool asset', 'en-US')
            ->setDescription('This asset is really cool', 'en-US')
            ->setFile($file, 'en-US');

        $this->assertJsonStringEqualsJsonString('{"fields":{"file":{"en-US":{"fileName":"testfile.jpg","contentType":"image/jpeg","upload":"https://www.example.com/testfile.jpeg"}},"title":{"en-US":"A cool asset"},"description":{"en-US":"This asset is really cool"}},"sys":{"type":"Asset"}}', json_encode($asset));
    }
}
