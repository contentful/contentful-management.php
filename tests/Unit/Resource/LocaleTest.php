<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Management\Resource;

use Contentful\Management\Resource\Locale;
use PHPUnit\Framework\TestCase;

class LocaleTest extends TestCase
{
    public function testGetSetData()
    {
        $locale = new Locale('Swiss Italian', 'it-CH');

        $this->assertEquals('Swiss Italian', $locale->getName());
        $locale->setName('Better Italian');
        $this->assertEquals('Better Italian', $locale->getName());

        $this->assertEquals('it-CH', $locale->getCode());
        $locale->setCode('it-IT');
        $this->assertEquals('it-IT', $locale->getCode());

        $this->assertFalse($locale->isOptional());
        $locale->setOptional(true);
        $this->assertTrue($locale->isOptional());

        $this->assertTrue($locale->isContentDeliveryApi());
        $locale->setContentDeliveryApi(false);
        $this->assertFalse($locale->isContentDeliveryApi());

        $this->assertTrue($locale->isContentManagementApi());
        $locale->setContentManagementApi(false);
        $this->assertFalse($locale->isContentManagementApi());

        $this->assertNull($locale->getFallbackCode());
        $locale->setFallbackCode('en-GB');
        $this->assertEquals('en-GB', $locale->getFallbackCode());

        $sys = $locale->getSystemProperties();
        $this->assertNull($sys->getId());
        $this->assertEquals('Locale', $sys->getType());
    }

    public function testJsonSerialize()
    {
        $locale = (new Locale('Swiss German', 'de-CH'))
            ->setContentDeliveryApi(false)
            ->setOptional(true)
            ->setFallbackCode('en-US');

        $this->assertJsonStringEqualsJsonString('{"sys": {"type": "Locale"}, "name": "Swiss German", "code": "de-CH", "fallbackCode": "en-US", "optional": true, "contentDeliveryApi": false, "contentManagementApi": true}', json_encode($locale));
    }
}
