<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\ContentType\Validation;

use Contentful\Management\Resource\ContentType\Validation\AssetImageDimensionsValidation;
use Contentful\Tests\Management\BaseTestCase;

class AssetImageDimensionsValidationTest extends BaseTestCase
{
    public function testJsonSerialize()
    {
        $validation = new AssetImageDimensionsValidation(100, 1000, 200, 2300);

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/ContentType/Validation/asset_image_dimentions_validation.json', $validation);
    }

    public function testGetSetData()
    {
        $validation = new AssetImageDimensionsValidation(50, 100, 60, 120);

        $this->assertSame(['Link'], $validation->getValidFieldTypes());

        $this->assertSame(50, $validation->getMinWidth());
        $this->assertSame(100, $validation->getMaxWidth());
        $this->assertSame(60, $validation->getMinHeight());
        $this->assertSame(120, $validation->getMaxHeight());

        $validation->setMinWidth(70);
        $this->assertSame(70, $validation->getMinWidth());

        $validation->setMaxWidth(140);
        $this->assertSame(140, $validation->getMaxWidth());

        $validation->setMinHeight(90);
        $this->assertSame(90, $validation->getMinHeight());

        $validation->setMaxHeight(180);
        $this->assertSame(180, $validation->getMaxHeight());

        $validation->setMinWidth(null);
        $this->assertNull($validation->getMinWidth());

        $validation->setMaxWidth(null);
        $this->assertNull($validation->getMaxWidth());

        $validation->setMinHeight(null);
        $this->assertNull($validation->getMinHeight());

        $validation->setMaxHeight(null);
        $this->assertNull($validation->getMaxHeight());
    }
}
