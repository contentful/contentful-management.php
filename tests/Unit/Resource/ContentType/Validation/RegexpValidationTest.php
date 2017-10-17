<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
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

        $this->assertEquals(['Text', 'Symbol'], $validation->getValidFieldTypes());

        $this->assertEquals('^such', $validation->getPattern());
        $this->assertEquals('im', $validation->getFlags());

        $validation->setPattern('much');
        $this->assertEquals('much', $validation->getPattern());

        $validation->setFlags('g');
        $this->assertEquals('g', $validation->getFlags());
    }
}
