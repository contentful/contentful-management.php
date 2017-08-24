<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\E2E;

use Contentful\Link;
use Contentful\Management\Resource\Asset;
use Contentful\Management\Resource\ContentType;
use Contentful\Management\Resource\Entry;
use Contentful\Management\Resource\PreviewApiKey;
use Contentful\Management\Resource\Role;
use Contentful\Management\Resource\Space;
use Contentful\Management\Resource\Webhook;
use Contentful\Tests\End2EndTestCase;

class ClientTest extends End2EndTestCase
{
    /**
     * @vcr e2e_client_link_resolver.json
     */
    public function testLinkResolver()
    {
        $manager = $this->getReadWriteSpaceManager();

        $link = new Link('2TEG7c2zYkSSuKmsqEwCS', 'Asset');
        $asset = $manager->resolveLink($link);
        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertEquals('Contentful Logo', $asset->getTitle('en-US'));

        $link = new Link('3LM5FlCdGUIM0Miqc664q6', 'Entry');
        $entry = $manager->resolveLink($link);
        $this->assertInstanceOf(Entry::class, $entry);
        $this->assertEquals('Josh Lyman', $entry->getField('name', 'en-US'));

        $link = new Link('person', 'ContentType');
        $contentType = $manager->resolveLink($link);
        $this->assertInstanceOf(ContentType::class, $contentType);
        $this->assertEquals('Person', $contentType->getName());

        $link = new Link('6khUMmsfVslYd7tRcThTgE', 'Role');
        $role = $manager->resolveLink($link);
        $this->assertInstanceOf(Role::class, $role);
        $this->assertEquals('Developer', $role->getName());

        $link = new Link('3tilCowN1lI1rDCe9vhK0C', 'WebhookDefinition');
        $webhook = $manager->resolveLink($link);
        $this->assertInstanceOf(Webhook::class, $webhook);
        $this->assertEquals('Default Webhook', $webhook->getName());

        $link = new Link('1Mx3FqXX5XCJDtNpVW4BZI', 'PreviewApiKey');
        $previewApiKey = $manager->resolveLink($link);
        $this->assertInstanceOf(PreviewApiKey::class, $previewApiKey);
        $this->assertEquals('Preview Key', $previewApiKey->getName());

        $link = new Link($this->readWriteSpaceId, 'Space');
        $space = $this->client->resolveLink($link);
        $this->assertInstanceOf(Space::class, $space);
        $this->assertEquals('PHP CMA', $space->getName());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unexpected system type "InvalidSystemType" while trying to resolve a Link
     */
    public function testInvalidLinkType()
    {
        $link = new Link('linkId', 'InvalidSystemType');
        $this->client->resolveLink($link, 'spaceId');
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Trying to resolve a link of a resource that is bound to a space, but not $spaceId parameter is given
     */
    public function testResolveEntryNeedsSpaceId()
    {
        $link = new Link('3LM5FlCdGUIM0Miqc664q6', 'Entry');
        $this->client->resolveLink($link);
    }
}
