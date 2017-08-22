<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Unit\FieldValidation;

use Contentful\Management\Field\Validation\AssetFileSizeValidation;
use PHPUnit\Framework\TestCase;

class AssetFileSizeValidationTest extends TestCase
{
    public function testFromJsonToJsonSerialization()
    {
        $jsonString = '{"assetFileSize": {"min": 1048576,"max": 8388608}}';
        $validation = AssetFileSizeValidation::fromApiResponse(json_decode($jsonString, true));

        $this->assertJsonStringEqualsJsonString($jsonString, json_encode($validation));
    }

    public function testGetSetData()
    {
        $validation = new AssetFileSizeValidation(5, 25);

        $this->assertEquals(5, $validation->getMin());
        $this->assertEquals(25, $validation->getMax());

        $validation->setMin(10);
        $this->assertEquals(10, $validation->getMin());

        $validation->setMax(450);
        $this->assertEquals(450, $validation->getMax());

        $validation->setMin(null);
        $this->assertNull($validation->getMin());

        $validation->setMax(null);
        $this->assertNull($validation->getMax());
    }
}
