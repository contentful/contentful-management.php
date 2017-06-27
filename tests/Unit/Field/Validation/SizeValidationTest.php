<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2017 Contentful GmbH
 * @license   MIT
 */

namespace Unit\FieldValidation;

use Contentful\Management\Field\Validation\SizeValidation;

class SizeValidationTest extends \PHPUnit_Framework_TestCase
{
    public function testFromJsonToJsonSerialization()
    {
        $jsonString = '{"size": { "min": 5, "max": 20}}';
        $validation = SizeValidation::fromApiResponse(json_decode($jsonString, true));

        $this->assertJsonStringEqualsJsonString($jsonString, json_encode($validation));
    }

    public function testGetSetData()
    {
        $validation = new SizeValidation(5, 20);

        $this->assertEquals(5, $validation->getMin());
        $this->assertEquals(20, $validation->getMax());

        $validation->setMin(17);
        $this->assertEquals(17, $validation->getMin());

        $validation->setMax(35);
        $this->assertEquals(35, $validation->getMax());
    }
}
