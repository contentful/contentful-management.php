<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit;

use Contentful\Management\SystemProperties;
use PHPUnit\Framework\TestCase;

class SystemPropertiesTest extends TestCase
{
    public function testCreateWithType()
    {
        $sys = SystemProperties::withType('Locale');
        $this->assertEquals('Locale', $sys->getType());

        $sys = SystemProperties::withType('Space');
        $this->assertEquals('Space', $sys->getType());
    }
}
