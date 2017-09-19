<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Management\Resource\ContentType\Validation;

use Contentful\Management\Resource\ContentType\Validation\SizeValidation;
use PHPUnit\Framework\TestCase;

class SizeValidationTest extends TestCase
{
    public function testJsonSerialize()
    {
        $validation = new SizeValidation(5, 20);

        $json = '{"size":{"min":5,"max":20}}';
        $this->assertJsonStringEqualsJsonString($json, json_encode($validation));
    }

    public function testGetSetData()
    {
        $validation = new SizeValidation(5, 20);

        $this->assertEquals(['Array', 'Text', 'Symbol'], $validation->getValidFieldTypes());

        $this->assertEquals(5, $validation->getMin());
        $this->assertEquals(20, $validation->getMax());

        $validation->setMin(17);
        $this->assertEquals(17, $validation->getMin());

        $validation->setMax(35);
        $this->assertEquals(35, $validation->getMax());
    }
}
