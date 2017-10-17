<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\ContentType\Validation;

use Contentful\Management\Resource\ContentType\Validation\SizeValidation;
use Contentful\Tests\Management\BaseTestCase;

class SizeValidationTest extends BaseTestCase
{
    public function testJsonSerialize()
    {
        $validation = new SizeValidation(5, 20);

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/ContentType/Validation/size_validation.json', $validation);
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
