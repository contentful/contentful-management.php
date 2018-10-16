<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2018 Contentful GmbH
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

        $this->assertSame('A space', $space->getName());

        $space->setName('A better space');
        $this->assertSame('A better space', $space->getName());

        $sys = $space->getSystemProperties();
        $this->assertSame('', $sys->getId());
        $this->assertSame('Space', $sys->getType());
    }

    public function testJsonSerialize()
    {
        $space = new Space('A space', 'organizationId');

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/space.json', $space);
    }
}
