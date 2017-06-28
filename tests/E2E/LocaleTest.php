<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\E2E;

use Contentful\Link;
use Contentful\Management\Locale;
use Contentful\Tests\End2EndTestCase;

class LocaleTest extends End2EndTestCase
{
    /**
     * @vcr e2e_locale_get_one.json
     */
    public function testGetLocale()
    {
        $manager = $this->getReadOnlySpaceManager();

        $locale = $manager->getLocale('2oQPjMCL9bQkylziydLh57');
        $this->assertEquals('English', $locale->getName());
        $this->assertEquals('en-US', $locale->getCode());
        $this->assertNull($locale->getFallbackCode());
        $this->assertTrue($locale->isContentDeliveryApi());
        $this->assertTrue($locale->isContentManagementApi());
        $this->assertFalse($locale->isOptional());
        $this->assertTrue($locale->isDefault());

        $sys = $locale->getSystemProperties();
        $this->assertEquals('2oQPjMCL9bQkylziydLh57', $sys->getId());
        $this->assertEquals('Locale', $sys->getType());
        $this->assertEquals(1, $sys->getVersion());
        $this->assertEquals(new Link($this->readOnlySpaceId, 'Space'), $sys->getSpace());
        $this->assertEquals(new \DateTimeImmutable('2013-06-23T19:02:00'), $sys->getCreatedAt());
        $this->assertEquals(new \DateTimeImmutable('2013-06-25T12:13:56'), $sys->getUpdatedAt());
        $this->assertEquals(new Link('7BslKh9TdKGOK41VmLDjFZ', 'User'), $sys->getCreatedBy());
        $this->assertEquals(new Link('7BslKh9TdKGOK41VmLDjFZ', 'User'), $sys->getUpdatedBy());

        $this->assertJsonStringEqualsJsonString('{"name":"English","code":"en-US","fallbackCode":null,"contentManagementApi":true,"contentDeliveryApi":true,"optional":false,"sys":{"type":"Locale","id":"2oQPjMCL9bQkylziydLh57","version":1,"space":{"sys":{"type":"Link","linkType":"Space","id":"cfexampleapi"}},"createdBy":{"sys":{"type":"Link","linkType":"User","id":"7BslKh9TdKGOK41VmLDjFZ"}},"createdAt":"2013-06-23T19:02:00Z","updatedBy":{"sys":{"type":"Link","linkType":"User","id":"7BslKh9TdKGOK41VmLDjFZ"}},"updatedAt":"2013-06-25T12:13:56Z"}}', json_encode($locale));
    }

    /**
     * @vcr e2e_locale_get_collection.json
     */
    public function testGetLocales()
    {
        $manager = $this->getReadOnlySpaceManager();

        $locales = $manager->getLocales();
        $this->assertCount(2, $locales);
        $this->assertInstanceOf(Locale::class, $locales[0]);
    }

    /**
     * @vcr e2e_locale_create_update_delete.json
     */
    public function testCreateUpdateDelete()
    {
        $manager = $this->getReadWriteSpaceManager();

        $locale = new Locale('Swiss German', 'de-CH');

        $manager->create($locale);
        $this->assertNotNull($locale->getSystemProperties()->getId());

        $locale->setName('Really Swiss German');
        $manager->update($locale);

        $manager->delete($locale);
    }

    /**
     * @vcr e2e_locale_create_with_id.json
     */
    public function testCreateLocaleWithGivenId()
    {
        $manager = $this->getReadWriteSpaceManager();

        $locale = new Locale('Swiss French', 'fr-CH');

        $manager->create($locale, 'swiss_french');
        $this->assertEquals('swiss_french', $locale->getSystemProperties()->getId());

        $manager->delete($locale);
    }
}
