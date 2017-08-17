<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Unit\FieldValidation;

use Contentful\Management\Field\Validation\LinkContentTypeValidation;
use PHPUnit\Framework\TestCase;

class LinkContentTypeValidationTest extends TestCase
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
