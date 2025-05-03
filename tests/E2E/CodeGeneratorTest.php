<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Core\Api\DateTimeImmutable;
use Contentful\Core\Api\Link;
use Contentful\Management\Console\Application;
use Contentful\Management\Resource\Asset;
use Contentful\Tests\Management\BaseTestCase;
use Contentful\Tests\Management\Fixtures\E2E\CodeGenerator\Author;
use Contentful\Tests\Management\Fixtures\E2E\CodeGenerator\BlogPost;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

class CodeGeneratorTest extends BaseTestCase
{
    public function testInvalidOutputDirectory()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Directory "/invalid-dir" does not exist and can not be created.');

        $application = new Application();

        $dir = '/invalid-dir';

        $command = $application->find('generate:entry-classes');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            '--access-token' => '<accessToken>',
            '--space-id' => '<spaceId>',
            '--environment-id' => '<environmentId>',
            '--dir' => $dir,
            '--namespace' => '',
        ]);
    }

    /**
     * @vcr e2e_code_generator.json
     */
    public function testCodeGenerator()
    {
        $application = new Application();

        $dir = \sys_get_temp_dir().'/contentful-management-'.\bin2hex(\random_bytes(5));

        $command = $application->find('generate:entry-classes');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            '--access-token' => $this->token,
            '--space-id' => $this->codeGeneratorSpaceId,
            '--environment-id' => 'master',
            '--dir' => $dir,
            '--namespace' => 'Contentful\\Tests\\Management\\Fixtures\\E2E\\CodeGenerator',
        ]);

        $output = $commandTester->getDisplay();

        $this->assertStringContainsStringIgnoringCase('Result of content type classes generator for space "'.$this->codeGeneratorSpaceId.'"', $output);
        $this->assertStringContainsStringIgnoringCase('* author (Author)', $output);
        $this->assertStringContainsStringIgnoringCase('- Entry '.$dir.'/Author.php', $output);
        $this->assertStringContainsStringIgnoringCase('- Mapper '.$dir.'/Mapper/AuthorMapper.php', $output);
        $this->assertStringContainsStringIgnoringCase('* blogPost (Blog Post)', $output);
        $this->assertStringContainsStringIgnoringCase('- Entry '.$dir.'/BlogPost.php', $output);
        $this->assertStringContainsStringIgnoringCase('- Mapper '.$dir.'/Mapper/BlogPostMapper.php', $output);
        $this->assertStringContainsStringIgnoringCase('Loader file generated at '.$dir.'/_loader.php', $output);

        $fixturesDir = __DIR__.'/../Fixtures/E2E/CodeGenerator';
        $this->assertSame(
            \file_get_contents($fixturesDir.'/_loader.php'),
            \file_get_contents($dir.'/_loader.php')
        );
        $this->assertSame(
            \file_get_contents($fixturesDir.'/Author.php'),
            \file_get_contents($dir.'/Author.php')
        );
        $this->assertSame(
            \file_get_contents($fixturesDir.'/Mapper/AuthorMapper.php'),
            \file_get_contents($dir.'/Mapper/AuthorMapper.php')
        );
        $this->assertSame(
            \file_get_contents($fixturesDir.'/BlogPost.php'),
            \file_get_contents($dir.'/BlogPost.php')
        );
        $this->assertSame(
            \file_get_contents($fixturesDir.'/Mapper/BlogPostMapper.php'),
            \file_get_contents($dir.'/Mapper/BlogPostMapper.php')
        );

        // Small cleanup
        (new Filesystem())->remove($dir);

        return $fixturesDir;
    }

    /**
     * @depends testCodeGenerator
     *
     * @vcr e2e_code_generator_create_delete.json
     */
    public function testGeneratedClassesWork(string $fixturesDir)
    {
        $client = $this->getClient();
        $proxy = $client->getEnvironmentProxy($this->codeGeneratorSpaceId, 'master');
        $builder = $client->getBuilder();

        require $fixturesDir.'/_loader.php';

        $author = new Author();
        $author->setName('en-US', 'Lee Adama');
        $author->setLocation('en-US', ['lat' => 50, 'lon' => 50]);
        $author->setIsActive('en-US', true);
        $author->setMisc('en-US', ['codename' => 'Apollo']);
        $author->setPicture('en-US', new Link('24jR8tPh6cWyQyWecs8USO', 'Asset'));
        $proxy->create($author);
        $this->assertNotNull($author->getId());
        $this->assertSame('Lee Adama', $author->getName('en-US'));
        $this->assertSame(['lon' => 50, 'lat' => 50], $author->getLocation('en-US'));
        $this->assertTrue($author->getIsActive('en-US'));
        $this->assertSame(['codename' => 'Apollo'], $author->getMisc('en-US'));
        $picture = $author->resolvePictureLink('en-US');
        $this->assertInstanceOf(Asset::class, $picture);
        $this->assertSame('Lee Adama', $picture->getTitle('en-US'));

        $blogPost = new BlogPost();
        $blogPost->setTitle('en-US', 'How to survive the deep space');
        $blogPost->setBody('en-US', 'You can\t.');
        $blogPost->setPublishedAt('en-US', new DateTimeImmutable('2017-10-06T11:30:00'));
        $blogPost->setImage('en-US', new Link('28Yop3scFS8U8EyWwOIoiy', 'Asset'));
        $blogPost->setRelated('en-US', []);
        $blogPost->setTags('en-US', ['space', 'survival']);
        $blogPost->setAuthor('en-US', $author->asLink());
        $proxy->create($blogPost);
        $this->assertNotNull($blogPost->getId());
        $this->assertSame('How to survive the deep space', $blogPost->getTitle('en-US'));
        $this->assertSame('You can\t.', $blogPost->getBody('en-US'));
        $this->assertSame('2017-10-06T11:30:00Z', (string) $blogPost->getPublishedAt('en-US'));
        $image = $blogPost->resolveImageLink('en-US');
        $this->assertInstanceOf(Asset::class, $image);
        $this->assertSame('Space', $image->getTitle('en-US'));
        $this->assertNull($blogPost->getRelated('en-US'));
        $this->assertSame(['space', 'survival'], $blogPost->getTags('en-US'));

        $blogPost->delete();
        $author->delete();
    }
}
