<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
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

        $this->assertSame(['Number', 'Integer'], $validation->getValidFieldTypes());

        $this->assertSame(5.0, $validation->getMin());
        $this->assertSame(20.0, $validation->getMax());

        $validation->setMin(17);
        $this->assertSame(17.0, $validation->getMin());

        $validation->setMax(null);
        $this->assertNull($validation->getMax());
    }

    public function testGetSetDataFloat()
    {
        $validation = new RangeValidation(5.0, 20.0);

        $this->assertSame(['Number', 'Integer'], $validation->getValidFieldTypes());

        $this->assertSame(5.0, $validation->getMin());
        $this->assertSame(20.0, $validation->getMax());

        $validation->setMin(17.0);
        $this->assertSame(17.0, $validation->getMin());

        $validation->setMax(null);
        $this->assertNull($validation->getMax());
    }
}
