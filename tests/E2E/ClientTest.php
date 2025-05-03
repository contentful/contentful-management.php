<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
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
        $this->assertIsInt($result);
        $this->assertGreaterThanOrEqual(2, $result);
    }

    /**
     * @vcr e2e_client_link_resolver.json
     */
    public function testLinkResolver()
    {
        $client = $this->getClient();
        $spaceProxy = $this->getReadOnlySpaceProxy();
        $environmentProxy = $this->getReadOnlyEnvironmentProxy();

        $link = new Link('2TEG7c2zYkSSuKmsqEwCS', 'Asset');
        $asset = $environmentProxy->resolveLink($link);
        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertSame('Contentful Logo', $asset->getTitle('en-US'));

        $link = new Link('3LM5FlCdGUIM0Miqc664q6', 'Entry');
        $entry = $environmentProxy->resolveLink($link);
        $this->assertInstanceOf(Entry::class, $entry);
        $this->assertSame('Josh Lyman', $entry->getField('name', 'en-US'));

        $link = new Link('person', 'ContentType');
        $contentType = $environmentProxy->resolveLink($link);
        $this->assertInstanceOf(ContentType::class, $contentType);
        $this->assertSame('Person', $contentType->getName());

        $link = new Link('6khUMmsfVslYd7tRcThTgE', 'Role');
        $role = $spaceProxy->resolveLink($link);
        $this->assertInstanceOf(Role::class, $role);
        $this->assertSame('Developer', $role->getName());

        $link = new Link('3tilCowN1lI1rDCe9vhK0C', 'WebhookDefinition');
        $webhook = $spaceProxy->resolveLink($link);
        $this->assertInstanceOf(Webhook::class, $webhook);
        $this->assertSame('Default Webhook', $webhook->getName());

        $link = new Link('1Mx3FqXX5XCJDtNpVW4BZI', 'PreviewApiKey');
        $previewApiKey = $spaceProxy->resolveLink($link);
        $this->assertInstanceOf(PreviewApiKey::class, $previewApiKey);
        $this->assertSame('Preview Key', $previewApiKey->getName());

        $link = new Link($this->readOnlySpaceId, 'Space');
        $space = $client->resolveLink($link);
        $this->assertInstanceOf(Space::class, $space);
        $this->assertSame('[PHP CMA] Read only', $space->getName());

        $links = [
            new Link('2TEG7c2zYkSSuKmsqEwCS', 'Asset'),
            new Link('3LM5FlCdGUIM0Miqc664q6', 'Entry'),
        ];
        $parameters = [
            'space' => $this->readOnlySpaceId,
            'environment' => 'master',
        ];
        $resources = $client->resolveLinkCollection($links, $parameters);
        $this->assertInstanceOf(Asset::class, $resources[0]);
        $this->assertSame('Contentful Logo', $resources[0]->getTitle('en-US'));
        $this->assertInstanceOf(Entry::class, $resources[1]);
        $this->assertSame('Josh Lyman', $resources[1]->getField('name', 'en-US'));
    }

    public function testCreateInvalidParameters()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Trying to make an API call on resource of class \"Contentful\Management\Resource\Entry\" without required parameters \"space, environment\".");

        $client = $this->getClient();

        $client->create(new Entry('someContentType'), '', ['invalidParameter' => 'invalidValue']);
    }

    /**
     * @vcr e2e_client_create_through_environment_object.json
     */
    public function testCreateThroughEnvironmentObject()
    {
        $client = $this->getClient();

        $environment = $client->getEnvironment($this->readWriteSpaceId, 'master');
        $asset = (new Asset())
            ->setTitle('en-US', 'A title')
        ;
        $client->create($asset, 'deleteme', $environment);

        $this->assertNotNull($asset->getId());
        $this->assertSame($environment->getId(), $asset->getSystemProperties()->getEnvironment()->getId());

        $asset->delete();
    }
}
