<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Core\Api\BaseClient;
use Contentful\Core\Api\Link;
use Contentful\Management\Resource\Asset;
use Contentful\Management\Resource\ContentType;
use Contentful\Management\Resource\Entry;
use Contentful\Management\Resource\PreviewApiKey;
use Contentful\Management\Resource\Role;
use Contentful\Management\Resource\Space;
use Contentful\Management\Resource\Webhook;
use Contentful\Tests\Management\BaseTestCase;

class ClientTest extends BaseTestCase
{
    public function testUserAgent()
    {
        $client = $this->getClient();

        $property = (new \ReflectionClass(BaseClient::class))->getProperty('userAgentGenerator');
        $property->setAccessible(true);
        $generator = $property->getValue($client);

        // PHP doesn't support the "g" modifier
        // so we can't use PHPUnit's assertRegExp method
        // and we need to rely on preg_match_all
        // which returns "false" if no matches are found,
        // or a number otherwise
        $result = \preg_match_all('/(app|sdk|platform|integration|os) \S+(\/\d+.\d+.\d+(-[\w\d-]+)?)?;/im', $generator->getUserAgent());
        $this->assertInternalType('int', $result);
        $this->assertGreaterThanOrEqual(2, $result);
    }

    /**
     * @vcr e2e_client_link_resolver.json
     */
    public function testLinkResolver()
    {
        $proxy = $this->getDefaultSpaceProxy();

        $link = new Link('2TEG7c2zYkSSuKmsqEwCS', 'Asset');
        $asset = $proxy->resolveLink($link);
        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertSame('Contentful Logo', $asset->getTitle('en-US'));

        $link = new Link('3LM5FlCdGUIM0Miqc664q6', 'Entry');
        $entry = $proxy->resolveLink($link);
        $this->assertInstanceOf(Entry::class, $entry);
        $this->assertSame('Josh Lyman', $entry->getField('name', 'en-US'));

        $link = new Link('person', 'ContentType');
        $contentType = $proxy->resolveLink($link);
        $this->assertInstanceOf(ContentType::class, $contentType);
        $this->assertSame('Person', $contentType->getName());

        $link = new Link('6khUMmsfVslYd7tRcThTgE', 'Role');
        $role = $proxy->resolveLink($link);
        $this->assertInstanceOf(Role::class, $role);
        $this->assertSame('Developer', $role->getName());

        $link = new Link('3tilCowN1lI1rDCe9vhK0C', 'WebhookDefinition');
        $webhook = $proxy->resolveLink($link);
        $this->assertInstanceOf(Webhook::class, $webhook);
        $this->assertSame('Default Webhook', $webhook->getName());

        $link = new Link('1Mx3FqXX5XCJDtNpVW4BZI', 'PreviewApiKey');
        $previewApiKey = $proxy->resolveLink($link);
        $this->assertInstanceOf(PreviewApiKey::class, $previewApiKey);
        $this->assertSame('Preview Key', $previewApiKey->getName());

        $link = new Link($this->defaultSpaceId, 'Space');
        $space = $proxy->resolveLink($link);
        $this->assertInstanceOf(Space::class, $space);
        $this->assertSame('PHP CMA', $space->getName());
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Unexpected system type "InvalidSystemType" while trying to resolve a Link.
     */
    public function testInvalidLinkType()
    {
        $link = new Link('linkId', 'InvalidSystemType');
        $this->getDefaultSpaceProxy()->resolveLink($link, 'spaceId');
    }

    /**
     * @expectedException        \RuntimeException
     * @expectedExceptionMessage Trying to create a resource of class "Contentful\Management\Resource\Entry" with incorrect set of parameters "invalidParameter".
     */
    public function testCreateInvalidParameters()
    {
        $client = $this->getClient();

        $client->create(new Entry('someContentType'), ['invalidParameter' => 'invalidValue']);
    }

    /**
     * @vcr e2e_client_create_through_space_object.json
     */
    public function testCreateThroughSpaceObject()
    {
        $client = $this->getClient();

        $space = $client->getSpace($this->defaultSpaceId);
        $entry = (new Entry('testCt'))
            ->setField('name', 'en-US', 'A name');

        $client->create($entry, $space, 'deleteme');

        $this->assertNotNull($entry->getId());
        $this->assertSame($space->getId(), $entry->getSystemProperties()->getSpace()->getId());

        $entry->delete();
    }
}
