<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\ContentType\Validation;

use Contentful\Management\Resource\ContentType\Validation\RegexpValidation;
use Contentful\Tests\Management\BaseTestCase;

class RegexpValidationTest extends BaseTestCase
{
    public function testJsonSerialize()
    {
        $validation = new RegexpValidation('^such', 'im');

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/ContentType/Validation/regexp_validation.json', $validation);
    }

    public function testGetSetData()
    {
        $validation = new RegexpValidation('^such', 'im');

        $this->assertSame(['Text', 'Symbol'], $validation->getValidFieldTypes());

        $this->assertSame('^such', $validation->getPattern());
        $this->assertSame('im', $validation->getFlags());

        $validation->setPattern('much');
        $this->assertSame('much', $validation->getPattern());

        $validation->setFlags('g');
        $this->assertSame('g', $validation->getFlags());
    }
}
