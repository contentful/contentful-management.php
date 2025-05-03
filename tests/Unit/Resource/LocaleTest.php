<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource;

use Contentful\Management\Resource\Locale;
use Contentful\Tests\Management\BaseTestCase;

class LocaleTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $locale = new Locale('Swiss Italian', 'it-CH');

        $this->assertSame('Swiss Italian', $locale->getName());
        $locale->setName('Better Italian');
        $this->assertSame('Better Italian', $locale->getName());

        $this->assertSame('it-CH', $locale->getCode());
        $locale->setCode('it-IT');
        $this->assertSame('it-IT', $locale->getCode());

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
        $this->assertSame('en-GB', $locale->getFallbackCode());
    }

    public function testJsonSerialize()
    {
        $locale = (new Locale('Swiss Italian', 'it-CH'))
            ->setContentDeliveryApi(false)
            ->setOptional(true)
            ->setFallbackCode('en-US')
        ;

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/locale.json', $locale);
    }
}
