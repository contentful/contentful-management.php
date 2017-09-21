<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Management\Resource\ContentType\Validation;

use Contentful\Management\Resource\ContentType\Validation\LinkContentTypeValidation;
use PHPUnit\Framework\TestCase;

class LinkContentTypeValidationTest extends TestCase
{
    public function testJsonSerialize()
    {
        $validation = new LinkContentTypeValidation(['post', 'doc', 'product']);

        $json = '{"linkContentType":["post","doc","product"]}';
        $this->assertJsonStringEqualsJsonString($json, json_encode($validation));
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
