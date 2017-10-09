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
use Contentful\Management\Generator\MapperGenerator;
use Contentful\Management\Proxy\BaseProxy;
use Contentful\Management\Resource\Asset;
use Contentful\Management\Resource\ContentType;
use Contentful\Management\Resource\ContentType\Validation\LinkContentTypeValidation;
use Contentful\Management\ResourceBuilder;
use Contentful\Management\SystemProperties;
use Contentful\Tests\Management\End2EndTestCase;
use Contentful\Tests\Management\Fixtures\Integration\Generator\BlogPost;
use Contentful\Tests\Management\Fixtures\Integration\Generator\Mapper\BlogPostMapper;
use function GuzzleHttp\json_encode;

class MapperGeneratorTest extends End2EndTestCase
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

        $generator = new MapperGenerator('en-US');
        $code = $generator->generate($contentType, 'Contentful\\Tests\\Management\\Fixtures\\Integration\\Generator');

        $expected = \file_get_contents(__DIR__.'/../../Fixtures/Integration/Generator/Mapper/BlogPostMapper.php');

        $this->assertEquals($expected, $code);
    }

    /**
     * @depends testGenerator
     */
    public function testGeneratedClassWorks()
    {
        $mapper = new BlogPostMapper(new ResourceBuilder());

        $data = [
            'sys' => [
                'id' => '<entryId>',
                'type' => 'Entry',
                'contentType' => [
                    'sys' => [
                        'id' => 'blogPost',
                        'linkType' => 'ContentType',
                        'type' => 'Link',
                    ],
                ],
            ],
            'fields' => [
                'title' => ['en-US' => 'title'],
                'isPublished' => ['en-US' => true],
                'publishedAt' => ['en-US' => '2017-10-06T09:30:30.123Z'],
                'previous' => ['en-US' => [
                    'sys' => [
                        'linkType' => 'Entry',
                        'id' => '<linkId>',
                        'type' => 'Link',
                    ],
                ]],
                'misc' => ['en-US' => [
                    'seasons' => [1, 2, 3, 4, 5, 6, '...and a movie!'],
                    'name' => 'Allura',
                    'job' => 'Princess and pilot of the Blue Lion',
                ]],
                'body' => ['en-US' => 'Now, this is a story all about how, my life got flipped-turned upside down'],
                'minimumAge' => ['en-US' => 18],
                'heroImage' => ['en-US' => [
                    'sys' => [
                        'linkType' => 'Asset',
                        'id' => '<linkId>',
                        'type' => 'Link',
                    ],
                ]],
                'randomEntry' => ['en-US' => [
                    'sys' => [
                        'linkType' => 'Entry',
                        'id' => '<linkId>',
                        'type' => 'Link',
                    ],
                ]],
                'location' => ['en-US' => [
                    'lat' => 10,
                    'lon' => 15,
                ]],
                'rating' => ['en-US' => 7.8],
                'images' => ['en-US' => [
                    ['sys' => [
                        'linkType' => 'Asset',
                        'id' => '<linkId1>',
                        'type' => 'Link',
                    ]],
                    ['sys' => [
                        'linkType' => 'Asset',
                        'id' => '<linkId2>',
                        'type' => 'Link',
                    ]],
                    ['sys' => [
                        'linkType' => 'Asset',
                        'id' => '<linkId3>',
                        'type' => 'Link',
                    ]],
                ]],
                'related' => ['en-US' => [
                    ['sys' => [
                        'linkType' => 'Entry',
                        'id' => '<linkId1>',
                        'type' => 'Link',
                    ]],
                    ['sys' => [
                        'linkType' => 'Entry',
                        'id' => '<linkId2>',
                        'type' => 'Link',
                    ]],
                    ['sys' => [
                        'linkType' => 'Entry',
                        'id' => '<linkId3>',
                        'type' => 'Link',
                    ]],
                ]],
                'tags' => ['en-US' => ['Fire Nation', 'Water Tribe', 'Earth Kingdom', 'Air Nomads']],
            ],
        ];
        $entry = $mapper->map(null, $data);
        $entry->setProxy(new MapperFakeProxy());

        $this->assertEquals('title', $entry->getTitle('en-US'));
        $this->assertTrue($entry->getIsPublished('en-US'));
        $this->assertEquals(new ApiDateTime('2017-10-06T09:30:30.123'), $entry->getPublishedAt('en-US'));
        $this->assertEquals(new Link('<linkId>', 'Entry'), $entry->getPrevious('en-US'));
        $this->assertEquals(new BlogPost(), $entry->resolvePreviousLink('en-US'));
        $this->assertEquals([
            'seasons' => [1, 2, 3, 4, 5, 6, '...and a movie!'],
            'name' => 'Allura',
            'job' => 'Princess and pilot of the Blue Lion',
        ], $entry->getMisc('en-US'));
        $this->assertEquals('Now, this is a story all about how, my life got flipped-turned upside down', $entry->getBody('en-US'));
        $this->assertEquals(18, $entry->getMinimumAge('en-US'));
        $this->assertEquals(new Link('<linkId>', 'Asset'), $entry->getHeroImage('en-US'));
        $this->assertEquals(new Asset(), $entry->resolveHeroImageLink('en-US'));
        $this->assertEquals(new Link('<linkId>', 'Entry'), $entry->getRandomEntry('en-US'));
        $this->assertEquals(new BlogPost(), $entry->resolveRandomEntryLink('en-US'));
        $this->assertEquals(['lat' => 10, 'lon' => 15], $entry->getLocation('en-US'));
        $this->assertEquals(7.8, $entry->getRating('en-US'));
        $this->assertEquals([
            new Link('<linkId1>', 'Asset'),
            new Link('<linkId2>', 'Asset'),
            new Link('<linkId3>', 'Asset'),
        ], $entry->getImages('en-US'));
        $this->assertEquals([
            new Asset(),
            new Asset(),
            new Asset(),
        ], $entry->resolveImagesLinks('en-US'));
        $this->assertEquals([
            new Link('<linkId1>', 'Entry'),
            new Link('<linkId2>', 'Entry'),
            new Link('<linkId3>', 'Entry'),
        ], $entry->getRelated('en-US'));
        $this->assertEquals([
            new BlogPost(),
            new BlogPost(),
            new BlogPost(),
        ], $entry->resolveRelatedLinks('en-US'));
        $this->assertEquals(['Fire Nation', 'Water Tribe', 'Earth Kingdom', 'Air Nomads'], $entry->getTags('en-US'));

        $json = '{"sys":{"id":"<entryId>","type":"Entry","contentType":{"sys":{"type":"Link","id":"blogPost","linkType":"ContentType"}}},"fields":{"title":{"en-US":"title"},"isPublished":{"en-US":true},"publishedAt":{"en-US":"2017-10-06T09:30:30.123Z"},"previous":{"en-US":{"sys":{"type":"Link","id":"<linkId>","linkType":"Entry"}}},"misc":{"en-US":{"seasons":[1,2,3,4,5,6,"...and a movie!"],"name":"Allura","job":"Princess and pilot of the Blue Lion"}},"body":{"en-US":"Now, this is a story all about how, my life got flipped-turned upside down"},"minimumAge":{"en-US":18},"heroImage":{"en-US":{"sys":{"type":"Link","id":"<linkId>","linkType":"Asset"}}},"randomEntry":{"en-US":{"sys":{"type":"Link","id":"<linkId>","linkType":"Entry"}}},"location":{"en-US":{"lat":10,"lon":15}},"rating":{"en-US":7.8},"images":{"en-US":[{"sys":{"type":"Link","id":"<linkId1>","linkType":"Asset"}},{"sys":{"type":"Link","id":"<linkId2>","linkType":"Asset"}},{"sys":{"type":"Link","id":"<linkId3>","linkType":"Asset"}}]},"related":{"en-US":[{"sys":{"type":"Link","id":"<linkId1>","linkType":"Entry"}},{"sys":{"type":"Link","id":"<linkId2>","linkType":"Entry"}},{"sys":{"type":"Link","id":"<linkId3>","linkType":"Entry"}}]},"tags":{"en-US":["Fire Nation","Water Tribe","Earth Kingdom","Air Nomads"]}}}';
        $this->assertJsonStringEqualsJsonString($json, json_encode($entry));
    }
}

class MapperFakeProxy extends BaseProxy
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
