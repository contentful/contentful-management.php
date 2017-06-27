<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\Unit;

use Contentful\Management\Space;

class SpaceTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetData()
    {
        $space = new Space('A space');

        $this->assertEquals('A space', $space->getName());

        $space->setName('A better space');
        $this->assertEquals('A better space', $space->getName());

        $sys = $space->getSystemProperties();
        $this->assertNull($sys->getId());
        $this->assertEquals('Space', $sys->getType());
    }

    public function testJsonSerialize()
    {
        $space = new Space('A space');

        $this->assertJsonStringEqualsJsonString('{"sys": {"type": "Space"}, "name": "A space"}', json_encode($space));
    }
}
