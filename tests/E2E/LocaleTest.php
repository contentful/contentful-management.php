<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Management\Resource\Locale;
use Contentful\Tests\Management\BaseTestCase;

class LocaleTest extends BaseTestCase
{
    /**
     * @vcr e2e_locale_get_one.json
     */
    public function testGetOne()
    {
        $proxy = $this->getReadOnlyEnvironmentProxy();

        $locale = $proxy->getLocale('6khdsfQbtrObkbrgWDTGe8');
        $this->assertLink('6khdsfQbtrObkbrgWDTGe8', 'Locale', $locale->asLink());
        $this->assertSame('U.S. English', $locale->getName());
        $this->assertSame('en-US', $locale->getCode());
        $this->assertNull($locale->getFallbackCode());
        $this->assertTrue($locale->isContentDeliveryApi());
        $this->assertTrue($locale->isContentManagementApi());
        $this->assertFalse($locale->isOptional());
        $this->assertTrue($locale->isDefault());

        $sys = $locale->getSystemProperties();
        $this->assertSame('6khdsfQbtrObkbrgWDTGe8', $sys->getId());
        $this->assertSame('Locale', $sys->getType());
        $this->assertSame(0, $sys->getVersion());
        $this->assertLink($this->readOnlySpaceId, 'Space', $sys->getSpace());
        $this->assertSame('2017-05-18T13:35:42Z', (string) $sys->getCreatedAt());
        $this->assertSame('2017-05-18T13:35:42Z', (string) $sys->getUpdatedAt());
        $this->assertLink('5wTIctqPekjOi9TGctNW7L', 'User', $sys->getCreatedBy());
        $this->assertLink('5wTIctqPekjOi9TGctNW7L', 'User', $sys->getUpdatedBy());
    }

    /**
     * @vcr e2e_locale_get_one_from_space_proxy.json
     */
    public function testGetOneFromSpaceProxy()
    {
        $proxy = $this->getReadOnlySpaceProxy();

        $locale = $proxy->getLocale('master', '6khdsfQbtrObkbrgWDTGe8');
        $this->assertLink('6khdsfQbtrObkbrgWDTGe8', 'Locale', $locale->asLink());
        $this->assertSame('U.S. English', $locale->getName());
        $this->assertSame('en-US', $locale->getCode());
        $this->assertNull($locale->getFallbackCode());
        $this->assertTrue($locale->isContentDeliveryApi());
        $this->assertTrue($locale->isContentManagementApi());
        $this->assertFalse($locale->isOptional());
        $this->assertTrue($locale->isDefault());

        $sys = $locale->getSystemProperties();
        $this->assertSame('6khdsfQbtrObkbrgWDTGe8', $sys->getId());
        $this->assertSame('Locale', $sys->getType());
        $this->assertSame(0, $sys->getVersion());
        $this->assertLink($this->readOnlySpaceId, 'Space', $sys->getSpace());
        $this->assertSame('2017-05-18T13:35:42Z', (string) $sys->getCreatedAt());
        $this->assertSame('2017-05-18T13:35:42Z', (string) $sys->getUpdatedAt());
        $this->assertLink('5wTIctqPekjOi9TGctNW7L', 'User', $sys->getCreatedBy());
        $this->assertLink('5wTIctqPekjOi9TGctNW7L', 'User', $sys->getUpdatedBy());
    }

    /**
     * @vcr e2e_locale_get_collection.json
     */
    public function testGetCollection()
    {
        $proxy = $this->getReadOnlyEnvironmentProxy();

        $locales = $proxy->getLocales();
        $this->assertCount(3, $locales);
        $this->assertInstanceOf(Locale::class, $locales[0]);
    }

    /**
     * @vcr e2e_locale_get_collection_from_space_proxy.json
     */
    public function testGetCollectionFromSpaceProxy()
    {
        $proxy = $this->getReadOnlySpaceProxy();

        $locales = $proxy->getLocales('master');
        $this->assertCount(3, $locales);
        $this->assertInstanceOf(Locale::class, $locales[0]);
    }

    /**
     * @vcr e2e_locale_create_update_delete.json
     */
    public function testCreateUpdateDelete()
    {
        $proxy = $this->getReadWriteEnvironmentProxy();

        $locale = new Locale('Swiss Italian', 'it-CH');

        $proxy->create($locale);
        $this->assertNotNull($locale->getId());

        $locale->setName('Really Swiss Italian');
        $locale->update();

        $locale->delete();
    }
}
