<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Management\Resource\ContentType\Validation;

use Contentful\Management\Resource\ContentType\Validation\LinkMimetypeGroupValidation;
use PHPUnit\Framework\TestCase;

class LinkMimetypeGroupValidationTest extends TestCase
{
    public function testJsonSerialize()
    {
        $validation = new LinkMimetypeGroupValidation(['image']);

        $json = '{"linkMimetypeGroup":["image"]}';
        $this->assertJsonStringEqualsJsonString($json, json_encode($validation));
    }

    public function testGetSetData()
    {
        $validation = new LinkMimetypeGroupValidation(['image']);

        $this->assertEquals(['Link'], $validation->getValidFieldTypes());

        $this->assertEquals(['image'], $validation->getMimeTypeGroups());

        $validation->setMimeTypeGroups(['audio', 'video']);
        $this->assertEquals(['audio', 'video'], $validation->getMimeTypeGroups());
    }
}
