<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\E2E;

use Contentful\Link;
use Contentful\Management\Client;
use Contentful\Management\Locale;
use Contentful\Management\Query;

class LocaleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $this->token = getenv('CONTENTFUL_CMA_TEST_TOKEN');
        $this->client = new Client($this->token);
    }

    /**
     * @vcr e2e_locale_get_one.json
     */
    public function testGetLocale()
    {
        $manager = $this->client->getSpaceManager('cfexampleapi');

        $locale = $manager->getLocale('2oQPjMCL9bQkylziydLh57');
        $this->assertEquals('English', $locale->getName());
        $this->assertEquals('en-US', $locale->getCode());
        $this->assertEquals('', $locale->getFallbackCode());
        $this->assertTrue($locale->isContentDeliveryApi());
        $this->assertTrue($locale->isContentManagementApi());
        $this->assertFalse($locale->isOptional());
        $this->assertTrue($locale->isDefault());

        $sys = $locale->getSystemProperties();
        $this->assertEquals('2oQPjMCL9bQkylziydLh57', $sys->getId());
        $this->assertEquals('Locale', $sys->getType());
        $this->assertEquals(1, $sys->getVersion());
        $this->assertEquals(new Link('cfexampleapi', 'Space'), $sys->getSpace());
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
        $manager = $this->client->getSpaceManager('cfexampleapi');

        $locales = $manager->getLocales();
        $this->assertCount(2, $locales);
        $this->assertInstanceOf(Locale::class, $locales[0]);
    }

    /**
     * @vcr e2e_locale_create_update_delete.json
     */
    public function testCreateUpdateDelete()
    {
        $manager = $this->client->getSpaceManager('34luz0flcmxt');

        $locale = new Locale('Swiss German', 'de-CH');

        $manager->create($locale);
        $this->assertNotNull($locale->getSystemProperties()->getId());

        $locale->setName('Really Swiss German');
        $manager->update($locale);

        $manager->delete($locale);
    }
}
