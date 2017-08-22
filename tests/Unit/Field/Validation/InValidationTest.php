<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Unit\FieldValidation;

use Contentful\Management\Field\Validation\InValidation;
use PHPUnit\Framework\TestCase;

class InValidationTest extends TestCase
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
