<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\ContentType\Validation;

use Contentful\Management\Resource\ContentType\Validation\AssetFileSizeValidation;
use Contentful\Tests\Management\BaseTestCase;

class AssetFileSizeValidationTest extends BaseTestCase
{
    public function testJsonSerialize()
    {
        $validation = new AssetFileSizeValidation(1048576, 8388608);

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/ContentType/Validation/asset_file_size_validation.json', $validation);
    }

    public function testGetSetData()
    {
        $validation = new AssetFileSizeValidation(5, 25);

        $this->assertSame(['Link'], $validation->getValidFieldTypes());

        $this->assertSame(5, $validation->getMin());
        $this->assertSame(25, $validation->getMax());

        $validation->setMin(10);
        $this->assertSame(10, $validation->getMin());

        $validation->setMax(450);
        $this->assertSame(450, $validation->getMax());

        $validation->setMin(null);
        $this->assertNull($validation->getMin());

        $validation->setMax(null);
        $this->assertNull($validation->getMax());
    }
}
