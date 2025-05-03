<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\ContentType\Validation;

use Contentful\Management\Resource\ContentType\Validation\InValidation;
use Contentful\Tests\Management\BaseTestCase;

class InValidationTest extends BaseTestCase
{
    public function testJsonSerialize()
    {
        $validation = new InValidation(['General', 'iOS', 'Android']);

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/ContentType/Validation/in_validation.json', $validation);
    }

    public function testGetSetData()
    {
        $validation = new InValidation(['General', 'iOS', 'Android']);

        $this->assertSame(['Text', 'Symbol', 'Integer', 'Number'], $validation->getValidFieldTypes());

        $this->assertSame(['General', 'iOS', 'Android'], $validation->getValues());

        $validation->setValues(['macOS', 'tvOS']);
        $this->assertSame(['macOS', 'tvOS'], $validation->getValues());
    }
}
