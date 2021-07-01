<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\ContentType\Validation;

use Contentful\Management\Resource\ContentType\Validation\EnabledMarksValidation;
use Contentful\Tests\Management\BaseTestCase;

class EnabledMarksValidationTest extends BaseTestCase
{
    public function testJsonSerialize()
    {
        $validation = new EnabledMarksValidation(['bold', 'code']);

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/ContentType/Validation/enabled_marks_validation.json', $validation);
    }

    public function testGetSetData()
    {
        $validation = new EnabledMarksValidation(['bold', 'code']);

        $this->assertSame(['RichText'], $validation->getValidFieldTypes());

        $this->assertSame(['bold', 'code'], $validation->getEnabledMarks());

        $validation->setEnabledMarks(['bold', 'italic']);
        $this->assertSame(['bold', 'italic'], $validation->getEnabledMarks());
    }
}
