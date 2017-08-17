<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Unit\FieldValidation;

use Contentful\Management\Field\Validation\DateRangeValidation;
use PHPUnit\Framework\TestCase;

class DateRangeValidationTest extends TestCase
{
    public function testFromJsonToJsonSerialization()
    {
        $jsonString = '{"dateRange": {"min": "2017-05-01","max": "2020-05-01"}}';
        $validation = DateRangeValidation::fromApiResponse(json_decode($jsonString, true));

        $this->assertJsonStringEqualsJsonString($jsonString, json_encode($validation));
    }

    public function testGetSetData()
    {
        $validation = new DateRangeValidation('1989-09-02', '2017-05-26');

        $this->assertEquals('1989-09-02', $validation->getMin());
        $this->assertEquals('2017-05-26', $validation->getMax());

        $validation->setMin('1998-03-27');
        $this->assertEquals('1998-03-27', $validation->getMin());

        $validation->setMax('2020-12-24');
        $this->assertEquals('2020-12-24', $validation->getMax());

        $validation->setMin(null);
        $this->assertNull($validation->getMin());

        $validation->setMax(null);
        $this->assertNull($validation->getMax());
    }
}
