<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Unit\FieldValidation;

use Contentful\Management\Field\Validation\LinkMimetypeGroupValidation;
use PHPUnit\Framework\TestCase;

class LinkMimetypeGroupValidationTest extends TestCase
{
    public function testFromJsonToJsonSerialization()
    {
        $jsonString = '{"linkMimetypeGroup": ["image"]}';
        $validation = LinkMimetypeGroupValidation::fromApiResponse(json_decode($jsonString, true));

        $this->assertJsonStringEqualsJsonString($jsonString, json_encode($validation));
    }

    public function testGetSetData()
    {
        $validation = new LinkMimetypeGroupValidation(['image']);

        $this->assertEquals(['image'], $validation->getMimeTypeGroups());

        $validation->setMimeTypeGroups(['audio', 'video']);
        $this->assertEquals(['audio', 'video'], $validation->getMimeTypeGroups());
    }
}
