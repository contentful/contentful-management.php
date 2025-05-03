<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\Extension;

use Contentful\Management\Resource\Extension\Parameter;
use Contentful\Tests\Management\BaseTestCase;

class ParameterTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $parameter = new Parameter('id', 'Name', 'Symbol');

        $this->assertSame('id', $parameter->getId());
        $this->assertSame('Name', $parameter->getName());
        $this->assertSame('Symbol', $parameter->getType());

        $parameter->setDescription('Some text');
        $this->assertSame('Some text', $parameter->getDescription());

        $this->assertFalse($parameter->isRequired());
        $parameter->setRequired(true);
        $this->assertTrue($parameter->isRequired());

        $parameter->setDefault('Some value');
        $this->assertSame('Some value', $parameter->getDefault());

        $parameter->setOptions(['one', 'two']);
        $this->assertSame(['one', 'two'], $parameter->getOptions());

        $parameter->setLabels(['some' => 'value', 'another' => 'something']);
        $this->assertSame(['some' => 'value', 'another' => 'something'], $parameter->getLabels());
    }

    public function testInvalidType()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid type "invalidType" given for parameter with ID "id".');

        new Parameter('id', 'Name', 'invalidType');
    }

    public function testJsonSerialize()
    {
        $parameter = (new Parameter('someId', 'Name', 'Symbol'))
            ->setDescription('Some description')
            ->setRequired(true)
            ->setDefault('Some default')
            ->setOptions(['one', 'two'])
            ->setLabels(['some' => 'value', 'another' => 'something'])
        ;

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/Extension/Parameter/serialize.json', $parameter);
    }
}
