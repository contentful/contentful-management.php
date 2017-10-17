<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource;

use Contentful\Management\Resource\Upload;
use Contentful\Tests\Management\BaseTestCase;

class UploadTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $upload = new Upload('text');
        $this->assertEquals('text', $upload->getBody());

        $upload->setBody('contents');
        $this->assertEquals('contents', $upload->getBody());
    }

    public function testJsonSerialize()
    {
        $upload = new Upload('contents');

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/upload.json', $upload);
    }
}
