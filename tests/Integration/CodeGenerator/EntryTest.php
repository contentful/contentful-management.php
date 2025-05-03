<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Integration\CodeGenerator;

use Contentful\Core\Api\DateTimeImmutable;
use Contentful\Core\Api\Link;
use Contentful\Management\CodeGenerator\Entry;
use Contentful\Management\Resource\Asset;
use Contentful\Management\Resource\ContentType;
use Contentful\Management\Resource\ContentType\Validation\LinkContentTypeValidation;
use Contentful\Management\SystemProperties\ContentType as SystemProperties;
use Contentful\Tests\Management\BaseTestCase;
use Contentful\Tests\Management\Fixtures\Integration\CodeGenerator\BlogPost;
use Contentful\Tests\Management\Implementation\EntryFakeClient;

class EntryTest extends BaseTestCase
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
        $contentType->addNewField('RichText', 'richtext', 'RichText');

        $generator = new Entry('en-US');
        $code = $generator->generate([
            'content_type' => $contentType,
            'namespace' => 'Contentful\\Tests\\Management\\Fixtures\\Integration\\CodeGenerator',
        ]);

        $expected = \file_get_contents(__DIR__.'/../../Fixtures/Integration/CodeGenerator/BlogPost.php');

        $this->assertSame($expected, $code);
    }

    /**
     * @depends testGenerator
     */
    public function testGenerateCodeWorks()
    {
        $client = new EntryFakeClient('irrelevant');
        $entry = new BlogPost();
        $entry->setClient($client);

        $sys = new SystemProperties([
            'space' => ['sys' => ['id' => 'irrelevant', 'linkType' => 'Space', 'type' => 'Link']],
            'environment' => ['sys' => ['id' => 'master', 'linkType' => 'Environment', 'type' => 'Link']],
            'contentType' => ['sys' => ['id' => 'irrelevant', 'linkType' => 'ContentType', 'type' => 'Link']],
            'createdAt' => '2018-01-01T12:00:00.123Z',
            'updatedAt' => '2018-01-01T12:00:00.123Z',
            'createdBy' => ['sys' => ['id' => 'irrelevant', 'linkType' => 'User', 'type' => 'Link']],
            'updatedBy' => ['sys' => ['id' => 'irrelevant', 'linkType' => 'User', 'type' => 'Link']],
            'version' => 1,
        ]);

        $reflection = new \ReflectionObject($entry);
        $property = $reflection->getProperty('sys');
        $property->setAccessible(true);
        $previousSys = $property->getValue($entry);
        $property->setValue($entry, $sys);

        $entry->setTitle('en-US', 'title');
        $this->assertSame('title', $entry->getTitle('en-US'));

        $entry->setIsPublished('en-US', true);
        $this->assertTrue($entry->getIsPublished('en-US'));

        $publishedAt = new DateTimeImmutable('2017-10-06T09:30:30.123');
        $entry->setPublishedAt('en-US', $publishedAt);
        $this->assertSame($publishedAt, $entry->getPublishedAt('en-US'));

        $previous = new Link('<linkId>', 'Entry');
        $entry->setPrevious('en-US', $previous);
        $this->assertSame($previous, $entry->getPrevious('en-US'));
        $this->assertInstanceOf(BlogPost::class, $entry->resolvePreviousLink('en-US'));

        $misc = [
            'seasons' => [1, 2, 3, 4, 5, 6, '...and a movie!'],
            'name' => 'Allura',
            'job' => 'Princess and pilot of the Blue Lion',
        ];
        $entry->setMisc('en-US', $misc);
        $this->assertSame($misc, $entry->getMisc('en-US'));

        $entry->setBody('en-US', 'Now, this is a story all about how, my life got flipped-turned upside down');
        $this->assertSame('Now, this is a story all about how, my life got flipped-turned upside down', $entry->getBody('en-US'));

        $entry->setMinimumAge('en-US', 18);
        $this->assertSame(18, $entry->getMinimumAge('en-US'));

        $heroImage = new Link('<linkId>', 'Asset');
        $entry->setHeroImage('en-US', $heroImage);
        $this->assertSame($heroImage, $entry->getHeroImage('en-US'));
        $this->assertInstanceOf(Asset::class, $entry->resolveHeroImageLink('en-US'));

        $randomEntry = new Link('<linkId>', 'Entry');
        $entry->setRandomEntry('en-US', $randomEntry);
        $this->assertSame($randomEntry, $entry->getRandomEntry('en-US'));
        $this->assertInstanceOf(BlogPost::class, $entry->resolveRandomEntryLink('en-US'));

        $location = ['lat' => 10, 'lon' => 15];
        $entry->setLocation('en-US', $location);
        $this->assertSame($location, $entry->getLocation('en-US'));

        // Too much water
        $entry->setRating('en-US', 7.8);
        $this->assertSame(7.8, $entry->getRating('en-US'));

        $images = [
            new Link('<linkId1>', 'Asset'),
            new Link('<linkId2>', 'Asset'),
            new Link('<linkId3>', 'Asset'),
        ];
        $entry->setImages('en-US', $images);
        $this->assertSame($images, $entry->getImages('en-US'));
        $images = $entry->resolveImagesLinks('en-US');
        $this->assertCount(3, $images);
        $this->assertContainsOnlyInstancesOf(Asset::class, $images);

        $related = [
            new Link('<linkId1>', 'Entry'),
            new Link('<linkId2>', 'Entry'),
            new Link('<linkId3>', 'Entry'),
        ];
        $entry->setRelated('en-US', $related);
        $this->assertSame($related, $entry->getRelated('en-US'));
        $blogPosts = $entry->resolveRelatedLinks('en-US');
        $this->assertCount(3, $blogPosts);
        $this->assertContainsOnlyInstancesOf(BlogPost::class, $blogPosts);

        $entry->setTags('en-US', ['Fire Nation', 'Water Tribe', 'Earth Kingdom', 'Air Nomads']);
        $this->assertSame(['Fire Nation', 'Water Tribe', 'Earth Kingdom', 'Air Nomads'], $entry->getTags('en-US'));

        // Restore previous sys values
        $property->setValue($entry, $previousSys);
        $this->assertJsonFixtureEqualsJsonObject('Integration/CodeGenerator/entry.json', $entry);
    }
}
