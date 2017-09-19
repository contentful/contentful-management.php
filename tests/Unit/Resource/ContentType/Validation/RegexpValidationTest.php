<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Management\Resource\ContentType\Validation;

use Contentful\Management\Resource\ContentType\Validation\RegexpValidation;
use PHPUnit\Framework\TestCase;

class RegexpValidationTest extends TestCase
{
    public function testJsonSerialize()
    {
        $validation = new RegexpValidation('^such', 'im');

        $json = '{"regexp":{"pattern":"^such","flags":"im"}}';
        $this->assertJsonStringEqualsJsonString($json, json_encode($validation));
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
