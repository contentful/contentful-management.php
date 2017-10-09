<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\ContentType\Validation;

use Contentful\Management\Resource\ContentType\Validation\UniqueValidation;
use PHPUnit\Framework\TestCase;

class UniqueValidationTest extends TestCase
{
    public function testJsonSerialize()
    {
        $validation = new UniqueValidation();

        $json = '{"unique":true}';
        $this->assertJsonStringEqualsJsonString($json, json_encode($validation));

        $this->assertEquals(['Symbol', 'Integer', 'Number'], $validation->getValidFieldTypes());
    }
}
