<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Unit\FieldValidation;

use Contentful\Management\Field\Validation\InValidation;

class InValidationTest extends \PHPUnit_Framework_TestCase
{
    public function testFromJsonToJsonSerialization()
    {
        $jsonString = '{"in": ["General", "iOS", "Android"]}';
        $validation = InValidation::fromApiResponse(json_decode($jsonString, true));

        $this->assertJsonStringEqualsJsonString($jsonString, json_encode($validation));
    }

    public function testGetSetData()
    {
        $validation = new InValidation(['General', 'iOS', 'Android']);

        $this->assertEquals(['General', 'iOS', 'Android'], $validation->getValues());

        $validation->setValues(['macOS', 'tvOS']);
        $this->assertEquals(['macOS', 'tvOS'], $validation->getValues());
    }
}
