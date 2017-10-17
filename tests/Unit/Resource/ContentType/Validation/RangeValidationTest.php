<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\ContentType\Validation;

use Contentful\Management\Resource\ContentType\Validation\RangeValidation;
use Contentful\Tests\Management\BaseTestCase;

class RangeValidationTest extends BaseTestCase
{
    public function testJsonSerialize()
    {
        $validation = new RangeValidation(5, 20);

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/ContentType/Validation/range_validation.json', $validation);
    }

    public function testGetSetData()
    {
        $validation = new RangeValidation(5, 20);

        $this->assertEquals(['Number', 'Integer'], $validation->getValidFieldTypes());

        $this->assertEquals(5, $validation->getMin());
        $this->assertEquals(20, $validation->getMax());

        $validation->setMin(17);
        $this->assertEquals(17, $validation->getMin());

        $validation->setMax(null);
        $this->assertNull($validation->getMax());
    }
}
