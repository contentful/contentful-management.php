<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\ContentType\Validation;

use Contentful\Management\Resource\ContentType\Validation\LinkContentTypeValidation;
use Contentful\Tests\Management\BaseTestCase;

class LinkContentTypeValidationTest extends BaseTestCase
{
    public function testJsonSerialize()
    {
        $validation = new LinkContentTypeValidation(['post', 'doc', 'product']);

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/ContentType/Validation/link_content_type_validation.json', $validation);
    }

    public function testGetSetData()
    {
        $validation = new LinkContentTypeValidation(['post', 'doc', 'product']);

        $this->assertEquals(['Link'], $validation->getValidFieldTypes());

        $this->assertEquals(['post', 'doc', 'product'], $validation->getContentTypes());

        $validation->setContentTypes(['cat', 'dog']);
        $this->assertEquals(['cat', 'dog'], $validation->getContentTypes());
    }
}
