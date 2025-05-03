<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Integration\CodeGenerator;

use Contentful\Management\CodeGenerator\Mapper;
use Contentful\Management\Resource\Asset;
use Contentful\Management\Resource\ContentType;
use Contentful\Management\Resource\ContentType\Validation\LinkContentTypeValidation;
use Contentful\Management\ResourceBuilder;
use Contentful\Management\SystemProperties\ContentType as SystemProperties;
use Contentful\Tests\Management\BaseTestCase;
use Contentful\Tests\Management\Fixtures\Integration\CodeGenerator\BlogPost;
use Contentful\Tests\Management\Fixtures\Integration\CodeGenerator\Mapper\BlogPostMapper;
use Contentful\Tests\Management\Implementation\MapperFakeClient;

class MapperTest extends BaseTestCase
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
            'type' => 'ContentType',
            'createdAt' => '2018-01-01T12:00:00.123Z',
            'updatedAt' => '2018-01-01T12:00:00.123Z',
            'createdBy' => [
                'sys' => [
                    'type' => 'Link',
                    'linkType' => 'User',
                    'id' => 'irrelevant',
                ],
            ],
            'updatedBy' => [
                'sys' => [
                    'type' => 'Link',
                    'linkType' => 'User',
                    'id' => 'irrelevant',
                ],
            ],
            'version' => 1,
            'space' => [
                'sys' => [
                    'type' => 'Link',
                    'linkType' => 'Space',
                    'id' => 'irrelevant',
                ],
            ],
            'environment' => [
                'sys' => [
                    'type' => 'Link',
                    'linkType' => 'Environment',
                    'id' => 'irrelevant',
                ],
            ],
        ]));

        $contentType->addNewField('Symbol', 'title', 'Title')->setRequired(true);
        $contentType->addNewField('Boolean', 'isPublished', 'Is published');
        $contentType->addNewField('Date', 'publishedAt', 'Published at');
        $contentType->addNewField('Link', 'previous', 'Previous', 'Entry')
            ->addValidation(new LinkContentTypeValidation(['blogPost']))
        ;
        $contentType->addNewField('Link', 'heroImage', 'Hero image', 'Asset');
        $contentType->addNewField('Link', 'randomEntry', 'Random entry', 'Entry');
        $contentType->addNewField('Object', 'misc', 'Misc');
        $contentType->addNewField('Text', 'body', 'Body');
        $contentType->addNewField('Integer', 'minimumAge', 'Minimum age');
        $contentType->addNewField('Location', 'location', 'Location');
        $contentType->addNewField('Number', 'rating', 'Rating');
        $contentType->addNewField('Array', 'images', 'Images', 'Link', 'Asset');
        $contentType->addNewField('Array', 'related', 'Related', 'Link', 'Entry')
            ->addItemsValidation(new LinkContentTypeValidation(['blogPost']))
        ;
        $contentType->addNewField('Array', 'tags', 'Tags', 'Symbol');

        $generator = new Mapper('en-US');
        $code = $generator->generate([
            'content_type' => $contentType,
            'namespace' => 'Contentful\\Tests\\Management\\Fixtures\\Integration\\CodeGenerator',
        ]);

        $expected = \file_get_contents(__DIR__.'/../../Fixtures/Integration/CodeGenerator/Mapper/BlogPostMapper.php');

        $this->assertSame($expected, $code);
    }

    /**
     * @depends testGenerator
     */
    public function testGenerateCodeWorks()
    {
        $mapper = new BlogPostMapper(new ResourceBuilder());

        $data = [
            'sys' => [
                'id' => '<entryId>',
                'type' => 'Entry',
                'createdAt' => '2018-01-01T12:00:00.123Z',
                'createdBy' => [
                    'sys' => [
                        'id' => 'irrelevant',
                        'linkType' => 'User',
                        'type' => 'Link',
                    ],
                ],
                'updatedAt' => '2018-01-01T12:00:00.123Z',
                'updatedBy' => [
                    'sys' => [
                        'id' => 'irrelevant',
                        'linkType' => 'User',
                        'type' => 'Link',
                    ],
                ],
                'version' => 1,
                'space' => [
                    'sys' => [
                        'id' => 'irrelevant',
                        'linkType' => 'Space',
                        'type' => 'Link',
                    ],
                ],
                'environment' => [
                    'sys' => [
                        'id' => 'master',
                        'linkType' => 'Environment',
                        'type' => 'Link',
                    ],
                ],
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
        $entry->setClient(new MapperFakeClient('irrelevant'));

        $this->assertSame('title', $entry->getTitle('en-US'));
        $this->assertTrue($entry->getIsPublished('en-US'));
        $this->assertSame('2017-10-06T09:30:30.123Z', (string) $entry->getPublishedAt('en-US'));
        $this->assertLink('<linkId>', 'Entry', $entry->getPrevious('en-US'));
        $this->assertInstanceOf(BlogPost::class, $entry->resolvePreviousLink('en-US'));
        $this->assertSame([
            'seasons' => [1, 2, 3, 4, 5, 6, '...and a movie!'],
            'name' => 'Allura',
            'job' => 'Princess and pilot of the Blue Lion',
        ], $entry->getMisc('en-US'));
        $this->assertSame('Now, this is a story all about how, my life got flipped-turned upside down', $entry->getBody('en-US'));
        $this->assertSame(18, $entry->getMinimumAge('en-US'));
        $this->assertLink('<linkId>', 'Asset', $entry->getHeroImage('en-US'));
        $this->assertInstanceOf(Asset::class, $entry->resolveHeroImageLink('en-US'));
        $this->assertLink('<linkId>', 'Entry', $entry->getRandomEntry('en-US'));
        $this->assertInstanceOf(BlogPost::class, $entry->resolveRandomEntryLink('en-US'));
        $this->assertSame(['lat' => 10, 'lon' => 15], $entry->getLocation('en-US'));
        $this->assertSame(7.8, $entry->getRating('en-US'));
        $this->assertLink('<linkId1>', 'Asset', $entry->getImages('en-US')[0]);
        $this->assertLink('<linkId2>', 'Asset', $entry->getImages('en-US')[1]);
        $this->assertLink('<linkId3>', 'Asset', $entry->getImages('en-US')[2]);
        $images = $entry->resolveImagesLinks('en-US');
        $this->assertCount(3, $images);
        $this->assertContainsOnlyInstancesOf(Asset::class, $images);
        $this->assertLink('<linkId1>', 'Entry', $entry->getRelated('en-US')[0]);
        $this->assertLink('<linkId2>', 'Entry', $entry->getRelated('en-US')[1]);
        $this->assertLink('<linkId3>', 'Entry', $entry->getRelated('en-US')[2]);
        $blogPosts = $entry->resolveRelatedLinks('en-US');
        $this->assertCount(3, $blogPosts);
        $this->assertContainsOnlyInstancesOf(BlogPost::class, $blogPosts);
        $this->assertSame(['Fire Nation', 'Water Tribe', 'Earth Kingdom', 'Air Nomads'], $entry->getTags('en-US'));

        $this->assertJsonFixtureEqualsJsonObject('Integration/CodeGenerator/mapper.json', $entry);
    }
}
