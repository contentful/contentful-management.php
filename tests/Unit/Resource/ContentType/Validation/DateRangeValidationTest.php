<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\ContentType\Validation;

use Contentful\Management\Resource\ContentType\Validation\DateRangeValidation;
use Contentful\Tests\Management\BaseTestCase;

class DateRangeValidationTest extends BaseTestCase
{
    public function testJsonSerialize()
    {
        $validation = new DateRangeValidation('2017-05-01', '2020-05-01');

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/ContentType/Validation/date_range_validation.json', $validation);
    }

    public function testGetSetData()
    {
        $validation = new DateRangeValidation('1989-09-02', '2017-05-26');

        $this->assertSame(['Date'], $validation->getValidFieldTypes());

        $this->assertSame('1989-09-02', $validation->getMin());
        $this->assertSame('2017-05-26', $validation->getMax());

        $validation->setMin('1998-03-27');
        $this->assertSame('1998-03-27', $validation->getMin());

        $validation->setMax('2020-12-24');
        $this->assertSame('2020-12-24', $validation->getMax());

        $validation->setMin(null);
        $this->assertNull($validation->getMin());

        $validation->setMax(null);
        $this->assertNull($validation->getMax());
    }
}
