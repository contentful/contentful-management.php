<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource;

use Contentful\Management\Resource\Space;
use Contentful\Tests\Management\BaseTestCase;

class SpaceTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $space = new Space('A space', 'organizationId');

        $this->assertEquals('A space', $space->getName());

        $space->setName('A better space');
        $this->assertEquals('A better space', $space->getName());

        $sys = $space->getSystemProperties();
        $this->assertNull($sys->getId());
        $this->assertEquals('Space', $sys->getType());
    }

    public function testJsonSerialize()
    {
        $space = new Space('A space', 'organizationId');

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/space.json', $space);
    }
}
