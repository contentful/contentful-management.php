<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Unit\FieldValidation;

use Contentful\Management\Field\Validation\RegexpValidation;

class RegexpValidationTest extends \PHPUnit_Framework_TestCase
{
    public function testFromJsonToJsonSerialization()
    {
        $jsonString = '{"regexp": {"pattern": "^such", "flags": "im"}}';
        $validation = RegexpValidation::fromApiResponse(json_decode($jsonString, true));

        $this->assertJsonStringEqualsJsonString($jsonString, json_encode($validation));
    }

    public function testGetSetData()
    {
        $validation = new RegexpValidation('^such', 'im');

        $this->assertEquals('^such', $validation->getPattern());
        $this->assertEquals('im', $validation->getFlags());

        $validation->setPattern('much');
        $this->assertEquals('much', $validation->getPattern());

        $validation->setFlags('g');
        $this->assertEquals('g', $validation->getFlags());
    }
}
