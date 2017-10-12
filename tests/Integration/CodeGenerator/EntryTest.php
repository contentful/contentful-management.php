<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\Integration\Generator;

use Contentful\Link;
use Contentful\Management\ApiDateTime;
use Contentful\Management\Client;
use Contentful\Management\CodeGenerator\Entry;
use Contentful\Management\Proxy\BaseProxy;
use Contentful\Management\Resource\Asset;
use Contentful\Management\Resource\ContentType;
use Contentful\Management\Resource\ContentType\Validation\LinkContentTypeValidation;
use Contentful\Management\SystemProperties;
use Contentful\Tests\Management\End2EndTestCase;
use Contentful\Tests\Management\Fixtures\Integration\CodeGenerator\BlogPost;
use function GuzzleHttp\json_encode;

class EntryTest extends End2EndTestCase
{
    public function testGenerator()
    {
        $contentType = new ContentType('Blog Post');

        // The generator works with the system ID, which is not usually accessible,
        // hence this hack
        $property = (new \ReflectionClass(ContentType::class))->getProperty('sys');
        $property->setAccessible(true);
        $property->setValue($contentType, new SystemProperties([
            'id' => 'blogPost',
            'type' => 'contentType',
        ]));

        $contentType->addNewField('Symbol', 'title', 'Title')->setRequired(true);
        $contentType->addNewField('Boolean', 'isPublished', 'Is published');
        $contentType->addNewField('Date', 'publishedAt', 'Published at');
        $contentType->addNewField('Link', 'previous', 'Previous', 'Entry')
            ->addValidation(new LinkContentTypeValidation(['blogPost']));
        $contentType->addNewField('Link', 'heroImage', 'Hero image', 'Asset');
        $contentType->addNewField('Link', 'randomEntry', 'Random entry', 'Entry');
        $contentType->addNewField('Object', 'misc', 'Misc');
        $contentType->addNewField('Text', 'body', 'Body');
        $contentType->addNewField('Integer', 'minimumAge', 'Minimum age');
        $contentType->addNewField('Location', 'location', 'Location');
        $contentType->addNewField('Number', 'rating', 'Rating');
        $contentType->addNewField('Array', 'images', 'Images', 'Link', 'Asset');
        $contentType->addNewField('Array', 'related', 'Related', 'Link', 'Entry')
            ->addItemsValidation(new LinkContentTypeValidation(['blogPost']));
        $contentType->addNewField('Array', 'tags', 'Tags', 'Symbol');

        $generator = new Entry('en-US');
        $code = $generator->generate([
            'content_type' => $contentType,
            'namespace' => 'Contentful\\Tests\\Management\\Fixtures\\Integration\\CodeGenerator',
        ]);

        $expected = \file_get_contents(__DIR__.'/../../Fixtures/Integration/CodeGenerator/BlogPost.php');

        $this->assertEquals($expected, $code);
    }

    /**
     * @depends testGenerator
     */
    public function testGeneratedClassWorks()
    {
        $entry = new BlogPost();
        $entry->setProxy(new EntryFakeProxy());

        $entry->setTitle('en-US', 'title');
        $this->assertEquals('title', $entry->getTitle('en-US'));

        $entry->setIsPublished('en-US', true);
        $this->assertTrue($entry->getIsPublished('en-US'));

        $publishedAt = new ApiDateTime('2017-10-06T09:30:30.123');
        $entry->setPublishedAt('en-US', $publishedAt);
        $this->assertSame($publishedAt, $entry->getPublishedAt('en-US'));

        $previous = new Link('<linkId>', 'Entry');
        $entry->setPrevious('en-US', $previous);
        $this->assertSame($previous, $entry->getPrevious('en-US'));
        $this->assertEquals(new BlogPost(), $entry->resolvePreviousLink('en-US'));

        $misc = [
            'seasons' => [1, 2, 3, 4, 5, 6, '...and a movie!'],
            'name' => 'Allura',
            'job' => 'Princess and pilot of the Blue Lion',
        ];
        $entry->setMisc('en-US', $misc);
        $this->assertEquals($misc, $entry->getMisc('en-US'));

        $entry->setBody('en-US', 'Now, this is a story all about how, my life got flipped-turned upside down');
        $this->assertEquals('Now, this is a story all about how, my life got flipped-turned upside down', $entry->getBody('en-US'));

        $entry->setMinimumAge('en-US', 18);
        $this->assertEquals(18, $entry->getMinimumAge('en-US'));

        $heroImage = new Link('<linkId>', 'Asset');
        $entry->setHeroImage('en-US', $heroImage);
        $this->assertEquals($heroImage, $entry->getHeroImage('en-US'));
        $this->assertEquals(new Asset(), $entry->resolveHeroImageLink('en-US'));

        $randomEntry = new Link('<linkId>', 'Entry');
        $entry->setRandomEntry('en-US', $randomEntry);
        $this->assertEquals($randomEntry, $entry->getRandomEntry('en-US'));
        $this->assertEquals(new BlogPost(), $entry->resolveRandomEntryLink('en-US'));

        $location = ['lat' => 10, 'lon' => 15];
        $entry->setLocation('en-US', $location);
        $this->assertEquals($location, $entry->getLocation('en-US'));

        // Too much water
        $entry->setRating('en-US', 7.8);
        $this->assertEquals(7.8, $entry->getRating('en-US'));

        $images = [
            new Link('<linkId1>', 'Asset'),
            new Link('<linkId2>', 'Asset'),
            new Link('<linkId3>', 'Asset'),
        ];
        $entry->setImages('en-US', $images);
        $this->assertEquals($images, $entry->getImages('en-US'));
        $this->assertEquals([
            new Asset(),
            new Asset(),
            new Asset(),
        ], $entry->resolveImagesLinks('en-US'));

        $related = [
            new Link('<linkId1>', 'Entry'),
            new Link('<linkId2>', 'Entry'),
            new Link('<linkId3>', 'Entry'),
        ];
        $entry->setRelated('en-US', $related);
        $this->assertEquals($related, $entry->getRelated('en-US'));
        $this->assertEquals([
            new BlogPost(),
            new BlogPost(),
            new BlogPost(),
        ], $entry->resolveRelatedLinks('en-US'));

        $entry->setTags('en-US', ['Fire Nation', 'Water Tribe', 'Earth Kingdom', 'Air Nomads']);
        $this->assertEquals(['Fire Nation', 'Water Tribe', 'Earth Kingdom', 'Air Nomads'], $entry->getTags('en-US'));

        $json = '{"sys":{"type":"Entry","contentType":{"sys":{"type":"Link","id":"blogPost","linkType":"ContentType"}}},"fields":{"title":{"en-US":"title"},"isPublished":{"en-US":true},"publishedAt":{"en-US":"2017-10-06T09:30:30.123Z"},"previous":{"en-US":{"sys":{"type":"Link","id":"<linkId>","linkType":"Entry"}}},"misc":{"en-US":{"seasons":[1,2,3,4,5,6,"...and a movie!"],"name":"Allura","job":"Princess and pilot of the Blue Lion"}},"body":{"en-US":"Now, this is a story all about how, my life got flipped-turned upside down"},"minimumAge":{"en-US":18},"heroImage":{"en-US":{"sys":{"type":"Link","id":"<linkId>","linkType":"Asset"}}},"randomEntry":{"en-US":{"sys":{"type":"Link","id":"<linkId>","linkType":"Entry"}}},"location":{"en-US":{"lat":10,"lon":15}},"rating":{"en-US":7.8},"images":{"en-US":[{"sys":{"type":"Link","id":"<linkId1>","linkType":"Asset"}},{"sys":{"type":"Link","id":"<linkId2>","linkType":"Asset"}},{"sys":{"type":"Link","id":"<linkId3>","linkType":"Asset"}}]},"related":{"en-US":[{"sys":{"type":"Link","id":"<linkId1>","linkType":"Entry"}},{"sys":{"type":"Link","id":"<linkId2>","linkType":"Entry"}},{"sys":{"type":"Link","id":"<linkId3>","linkType":"Entry"}}]},"tags":{"en-US":["Fire Nation","Water Tribe","Earth Kingdom","Air Nomads"]}}}';
        $this->assertJsonStringEqualsJsonString($json, json_encode($entry));
    }
}

class EntryFakeProxy extends BaseProxy
{
    /**
     * {@inheritdoc}
     */
    protected $requiresSpaceId = false;

    /**
     * {@inheritdoc}
     */
    public function __construct(Client $client = null, string $spaceId = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getResourceUri(array $values): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabledMethods(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function resolveLink(Link $link)
    {
        if ($link->getLinkType() == 'Asset') {
            return new Asset();
        }

        if ($link->getLinkType() == 'Entry') {
            return new BlogPost();
        }
    }
}
