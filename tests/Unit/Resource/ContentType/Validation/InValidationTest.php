<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Management\Resource\ContentType\Validation;

use Contentful\Management\Resource\ContentType\Validation\InValidation;
use PHPUnit\Framework\TestCase;

class InValidationTest extends TestCase
{
    public function testJsonSerialize()
    {
        $validation = new InValidation(['General', 'iOS', 'Android']);

        $json = '{"in":["General","iOS","Android"]}';
        $this->assertJsonStringEqualsJsonString($json, json_encode($validation));
    }

    public function testGetSetData()
    {
        $validation = new InValidation(['General', 'iOS', 'Android']);

        $this->assertEquals(['Text', 'Symbol', 'Integer', 'Number'], $validation->getValidFieldTypes());

        $this->assertEquals(['General', 'iOS', 'Android'], $validation->getValues());

        $validation->setValues(['macOS', 'tvOS']);
        $this->assertEquals(['macOS', 'tvOS'], $validation->getValues());
    }
}
