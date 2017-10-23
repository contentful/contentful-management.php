<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Client;
use Contentful\File\RemoteUploadFile;
use Contentful\Link;
use Contentful\Management\Exception\InvalidProxyActionException;
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
        $client = $this->getUnboundClient();

        $property = (new \ReflectionClass(Client::class))->getProperty('userAgentGenerator');
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
        $client = $this->getDefaultClient();

        $link = new Link('2TEG7c2zYkSSuKmsqEwCS', 'Asset');
        $asset = $client->resolveLink($link);
        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertEquals('Contentful Logo', $asset->getTitle('en-US'));

        $link = new Link('3LM5FlCdGUIM0Miqc664q6', 'Entry');
        $entry = $client->resolveLink($link);
        $this->assertInstanceOf(Entry::class, $entry);
        $this->assertEquals('Josh Lyman', $entry->getField('name', 'en-US'));

        $link = new Link('person', 'ContentType');
        $contentType = $client->resolveLink($link);
        $this->assertInstanceOf(ContentType::class, $contentType);
        $this->assertEquals('Person', $contentType->getName());

        $link = new Link('6khUMmsfVslYd7tRcThTgE', 'Role');
        $role = $client->resolveLink($link);
        $this->assertInstanceOf(Role::class, $role);
        $this->assertEquals('Developer', $role->getName());

        $link = new Link('3tilCowN1lI1rDCe9vhK0C', 'WebhookDefinition');
        $webhook = $client->resolveLink($link);
        $this->assertInstanceOf(Webhook::class, $webhook);
        $this->assertEquals('Default Webhook', $webhook->getName());

        $link = new Link('1Mx3FqXX5XCJDtNpVW4BZI', 'PreviewApiKey');
        $previewApiKey = $client->resolveLink($link);
        $this->assertInstanceOf(PreviewApiKey::class, $previewApiKey);
        $this->assertEquals('Preview Key', $previewApiKey->getName());

        $link = new Link($this->defaultSpaceId, 'Space');
        $space = $client->resolveLink($link);
        $this->assertInstanceOf(Space::class, $space);
        $this->assertEquals('PHP CMA', $space->getName());
    }

    public function testCurrentSpaceId()
    {
        $client = $this->getUnboundClient();

        $client->setCurrentSpaceId('spaceId');
        $this->assertEquals('spaceId', $client->getCurrentSpaceId());

        $client->setCurrentSpaceId(null);
        $this->assertNull($client->getCurrentSpaceId());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Trying to access invalid proxy "invalidProxy".
     */
    public function testInvalidProxy()
    {
        $client = $this->getUnboundClient();

        $client->invalidProxy;
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unexpected system type "InvalidSystemType" while trying to resolve a Link.
     */
    public function testInvalidLinkType()
    {
        $link = new Link('linkId', 'InvalidSystemType');
        $this->getUnboundClient()->resolveLink($link, 'spaceId');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Trying to access proxy "Contentful\Management\Proxy\Entry" which requires a space ID, but none is given.
     */
    public function testResolveEntryNeedsSpaceId()
    {
        $link = new Link('3LM5FlCdGUIM0Miqc664q6', 'Entry');
        $this->getUnboundClient()->resolveLink($link);
    }

    /**
     * @dataProvider proxyMethodsProvider
     */
    public function testEnabledMethods(string $proxy, array $methods)
    {
        $client = $this->getDefaultClient();

        $this->assertEquals($methods, $client->getProxy($proxy)->getEnabledMethods(), '', 0.0, 10, true);
    }

    public function proxyMethodsProvider()
    {
        return [
            ['asset', ['create', 'update', 'delete', 'publish', 'unpublish', 'archive', 'unarchive', 'process']],
            ['contentType', ['create', 'update', 'delete', 'publish', 'unpublish']],
            ['contentTypeSnapshot', []],
            ['editorInterface', ['update']],
            ['deliveryApiKey', ['create', 'update', 'delete']],
            ['entry', ['create', 'update', 'delete', 'publish', 'unpublish', 'archive', 'unarchive']],
            ['entrySnapshot', []],
            ['locale', ['create', 'update', 'delete']],
            ['organization', []],
            ['personalAccessToken', ['create', 'revoke']],
            ['previewApiKey', []],
            ['publishedContentType', []],
            ['role', ['create', 'update', 'delete']],
            ['space', ['create', 'update', 'delete']],
            ['spaceMembership', ['create', 'update', 'delete']],
            ['upload', ['create', 'delete']],
            ['user', []],
            ['webhook', ['create', 'update', 'delete']],
            ['webhookCall', []],
            ['webhookHealth', []],
        ];
    }

    /**
     * @expectedException \Contentful\Exception\NotFoundException
     * @expectedExceptionMessage The resource could not be found.
     * @vcr e2e_client_proxy_methods_without_objects.json
     */
    public function testProxyMethodsWithoutObjects()
    {
        $proxy = $this->getDefaultClient()->asset;

        $asset = new Asset();
        $asset->setTitle('en-US', 'My asset');
        $asset->setDescription('en-US', 'My description');
        $file = new RemoteUploadFile('contentful.svg', 'image/svg+xml', 'https://pbs.twimg.com/profile_images/488880764323250177/CrqV-RjR_normal.jpeg');
        $asset->setFile('en-US', $file);

        $proxy->create($asset);
        $assetId = $asset->getId();
        $this->assertNotNull($assetId);

        $proxy->delete($assetId, 1);

        $asset = $proxy->get($assetId);
    }

    /**
     * @dataProvider invalidActionObjectsProvider
     *
     * @param object $object
     * @param string $method
     * @param string $message
     */
    public function testProxyActionOnInvalidObject($object, string $method, string $message)
    {
        $this->expectException(InvalidProxyActionException::class);
        $this->expectExceptionMessage($message);

        $proxy = $this->getDefaultClient()->asset;

        $proxy->{$method}($object);
    }

    public function invalidActionObjectsProvider()
    {
        return [
            [new \stdClass(), 'delete', 'Trying to perform invalid action "delete" on proxy "Contentful\Management\Proxy\Asset" with argument of class "stdClass".'],
            [new \stdClass(), 'archive', 'Trying to perform invalid action "archive" on proxy "Contentful\Management\Proxy\Asset" with argument of class "stdClass".'],
            [new \stdClass(), 'unarchive', 'Trying to perform invalid action "unarchive" on proxy "Contentful\Management\Proxy\Asset" with argument of class "stdClass".'],
            [new \stdClass(), 'publish', 'Trying to perform invalid action "publish" on proxy "Contentful\Management\Proxy\Asset" with argument of class "stdClass".'],
            [new \stdClass(), 'unpublish', 'Trying to perform invalid action "unpublish" on proxy "Contentful\Management\Proxy\Asset" with argument of class "stdClass".'],
            [new \stdClass(), 'invalidAction', 'Trying to perform invalid action "invalidAction" on proxy "Contentful\Management\Proxy\Asset".'],
        ];
    }
}
