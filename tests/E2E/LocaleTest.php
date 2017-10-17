<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Link;
use Contentful\Management\ApiDateTime;
use Contentful\Management\Resource\Locale;
use Contentful\Tests\Management\BaseTestCase;

class LocaleTest extends BaseTestCase
{
    /**
     * @vcr e2e_locale_get_one.json
     */
    public function testGetLocale()
    {
        $client = $this->getDefaultClient();

        $locale = $client->locale->get('6khdsfQbtrObkbrgWDTGe8');
        $this->assertEquals(new Link('6khdsfQbtrObkbrgWDTGe8', 'Locale'), $locale->asLink());
        $this->assertEquals('U.S. English', $locale->getName());
        $this->assertEquals('en-US', $locale->getCode());
        $this->assertNull($locale->getFallbackCode());
        $this->assertTrue($locale->isContentDeliveryApi());
        $this->assertTrue($locale->isContentManagementApi());
        $this->assertFalse($locale->isOptional());
        $this->assertTrue($locale->isDefault());

        $sys = $locale->getSystemProperties();
        $this->assertEquals('6khdsfQbtrObkbrgWDTGe8', $sys->getId());
        $this->assertEquals('Locale', $sys->getType());
        $this->assertEquals(0, $sys->getVersion());
        $this->assertEquals(new Link($this->defaultSpaceId, 'Space'), $sys->getSpace());
        $this->assertEquals(new ApiDateTime('2017-05-18T13:35:42'), $sys->getCreatedAt());
        $this->assertEquals(new ApiDateTime('2017-05-18T13:35:42'), $sys->getUpdatedAt());
        $this->assertEquals(new Link('5wTIctqPekjOi9TGctNW7L', 'User'), $sys->getCreatedBy());
        $this->assertEquals(new Link('5wTIctqPekjOi9TGctNW7L', 'User'), $sys->getUpdatedBy());
    }

    /**
     * @vcr e2e_locale_get_collection.json
     */
    public function testGetLocales()
    {
        $client = $this->getDefaultClient();

        $locales = $client->locale->getAll();
        $this->assertCount(3, $locales);
        $this->assertInstanceOf(Locale::class, $locales[0]);
    }

    /**
     * @vcr e2e_locale_create_update_delete.json
     */
    public function testCreateUpdateDelete()
    {
        $client = $this->getDefaultClient();

        $locale = new Locale('Swiss Italian', 'it-CH');

        $client->locale->create($locale);
        $this->assertNotNull($locale->getId());

        $locale->setName('Really Swiss Italian');
        $locale->update();

        $locale->delete();
    }
}
