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
use Contentful\Tests\Management\End2EndTestCase;

class LocaleTest extends End2EndTestCase
{
    /**
     * @vcr e2e_locale_get_one.json
     */
    public function testGetLocale()
    {
        $client = $this->getReadOnlyClient();

        $locale = $client->locale->get('2oQPjMCL9bQkylziydLh57');
        $this->assertEquals(new Link('2oQPjMCL9bQkylziydLh57', 'Locale'), $locale->asLink());
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
        $this->assertEquals(new ApiDateTime('2013-06-23T19:02:00'), $sys->getCreatedAt());
        $this->assertEquals(new ApiDateTime('2013-06-25T12:13:56'), $sys->getUpdatedAt());
        $this->assertEquals(new Link('7BslKh9TdKGOK41VmLDjFZ', 'User'), $sys->getCreatedBy());
        $this->assertEquals(new Link('7BslKh9TdKGOK41VmLDjFZ', 'User'), $sys->getUpdatedBy());
    }

    /**
     * @vcr e2e_locale_get_collection.json
     */
    public function testGetLocales()
    {
        $client = $this->getReadOnlyClient();

        $locales = $client->locale->getAll();
        $this->assertCount(2, $locales);
        $this->assertInstanceOf(Locale::class, $locales[0]);
    }

    /**
     * @vcr e2e_locale_create_update_delete.json
     */
    public function testCreateUpdateDelete()
    {
        $client = $this->getReadWriteClient();

        $locale = new Locale('Swiss Italian', 'it-CH');

        $client->locale->create($locale);
        $this->assertNotNull($locale->getId());

        $locale->setName('Really Swiss Italian');
        $locale->update();

        $locale->delete();
    }
}
