<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Unit\FieldValidation;

use Contentful\Management\Field\Validation\LinkContentTypeValidation;

class LinkContentTypeValidationTest extends \PHPUnit_Framework_TestCase
{
    public function testFromJsonToJsonSerialization()
    {
        $jsonString = '{"linkContentType": ["post","doc","product"]}';
        $validation = LinkContentTypeValidation::fromApiResponse(json_decode($jsonString, true));

        $this->assertJsonStringEqualsJsonString($jsonString, json_encode($validation));
    }

    public function testGetSetData()
    {
        $validation = new LinkContentTypeValidation(['post', 'doc', 'product']);

        $this->assertEquals(['post', 'doc', 'product'], $validation->getContentTypes());

        $validation->setContentTypes(['cat', 'dog']);
        $this->assertEquals(['cat', 'dog'], $validation->getContentTypes());
    }
}
