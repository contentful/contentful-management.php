<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\ContentType\Validation;

use Contentful\Management\Resource\ContentType\Validation\UniqueValidation;
use Contentful\Tests\Management\BaseTestCase;

class UniqueValidationTest extends BaseTestCase
{
    public function testJsonSerialize()
    {
        $validation = new UniqueValidation();

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/ContentType/Validation/unique_validation.json', $validation);

        $this->assertSame(['Symbol', 'Integer', 'Number'], $validation->getValidFieldTypes());
    }
}
