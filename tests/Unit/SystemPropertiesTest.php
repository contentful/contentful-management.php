<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\Unit;

use Contentful\Management\SystemProperties;

class SystemPropertiesTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateWithType()
    {
        $sys = SystemProperties::withType('Locale');
        $this->assertEquals('Locale', $sys->getType());

        $sys = SystemProperties::withType('Space');
        $this->assertEquals('Space', $sys->getType());
    }
}
