<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Unit\FieldValidation;

use Contentful\Management\Field\Validation\AssetImageDimensionsValidation;
use PHPUnit\Framework\TestCase;

class AssetImageDimensionsValidationTest extends TestCase
{
    public function testFromJsonToJsonSerialization()
    {
        $jsonString = '{"assetImageDimensions": {"width": {"min": 100,"max": 1000},"height": {"min": 200,"max": 2300}}}';
        $validation = AssetImageDimensionsValidation::fromApiResponse(json_decode($jsonString, true));

        $this->assertJsonStringEqualsJsonString($jsonString, json_encode($validation));
    }

    public function testGetSetData()
    {
        $validation = new AssetImageDimensionsValidation(50, 100, 60, 120);

        $this->assertEquals(50, $validation->getMinWidth());
        $this->assertEquals(100, $validation->getMaxWidth());
        $this->assertEquals(60, $validation->getMinHeight());
        $this->assertEquals(120, $validation->getMaxHeight());

        $validation->setMinWidth(70);
        $this->assertEquals(70, $validation->getMinWidth());

        $validation->setMaxWidth(140);
        $this->assertEquals(140, $validation->getMaxWidth());

        $validation->setMinHeight(90);
        $this->assertEquals(90, $validation->getMinHeight());

        $validation->setMaxHeight(180);
        $this->assertEquals(180, $validation->getMaxHeight());

        $validation->setMinWidth(null);
        $this->assertNull($validation->getMinWidth());

        $validation->setMaxWidth(null);
        $this->assertNull($validation->getMaxWidth());

        $validation->setMinHeight(null);
        $this->assertNull($validation->getMinHeight());

        $validation->setMaxHeight(null);
        $this->assertNull($validation->getMaxHeight());
    }
}
