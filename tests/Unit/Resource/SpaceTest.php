<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Resource;

use Contentful\Management\Resource\Space;
use PHPUnit\Framework\TestCase;

class SpaceTest extends TestCase
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
