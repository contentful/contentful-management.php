<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2017 Contentful GmbH
 * @license   MIT
 */

namespace Unit\FieldValidation;

use Contentful\Management\Field\Validation\LinkMimetypeGroupValidation;

class LinkMimetypeGroupValidationTest extends \PHPUnit_Framework_TestCase
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
