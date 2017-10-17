<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Link;
use Contentful\Management\ApiDateTime;
use Contentful\Management\Console\Application;
use Contentful\Management\Resource\Asset;
use Contentful\Tests\Management\BaseTestCase;
use Contentful\Tests\Management\Fixtures\E2E\CodeGenerator\Author;
use Contentful\Tests\Management\Fixtures\E2E\CodeGenerator\BlogPost;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

class CodeGenerationTest extends BaseTestCase
{
    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Directoy "/invalid-dir" does not exist and can not be created.
     */
    public function testInvalidOutputDirectory()
    {
        $application = new Application();

        $dir = '/invalid-dir';

        $command = $application->find('generate:entry-classes');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'space-id' => '<spaceId>',
            'token' => '<accessToken>',
            'dir' => $dir,
            'namespace' => '',
        ]);
    }

    /**
     * @vcr e2e_code_generation.json
     */
    public function testCodeGeneration()
    {
        $application = new Application();

        $dir = \sys_get_temp_dir().'/contentful-management-'.\bin2hex(\random_bytes(5));

        $command = $application->find('generate:entry-classes');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'space-id' => $this->codeGenerationSpaceId,
            'token' => $this->token,
            'dir' => $dir,
            'namespace' => 'Contentful\\Tests\\Management\\Fixtures\\E2E\\CodeGenerator',
        ]);

        $output = $commandTester->getDisplay();

        $this->assertContains('Result of content type classes generation for space "'.$this->codeGenerationSpaceId.'"', $output);
        $this->assertContains('* author (Author)', $output);
        $this->assertContains('- Entry '.$dir.'/Author.php', $output);
        $this->assertContains('- Mapper '.$dir.'/Mapper/AuthorMapper.php', $output);
        $this->assertContains('* blogPost (Blog Post)', $output);
        $this->assertContains('- Entry '.$dir.'/BlogPost.php', $output);
        $this->assertContains('- Mapper '.$dir.'/Mapper/BlogPostMapper.php', $output);
        $this->assertContains('Loader file generated at '.$dir.'/_loader.php', $output);

        $fixturesDir = __DIR__.'/../Fixtures/E2E/CodeGenerator';
        $this->assertEquals(
            \file_get_contents($fixturesDir.'/_loader.php'),
            \file_get_contents($dir.'/_loader.php')
        );
        $this->assertEquals(
            \file_get_contents($fixturesDir.'/Author.php'),
            \file_get_contents($dir.'/Author.php')
        );
        $this->assertEquals(
            \file_get_contents($fixturesDir.'/Mapper/AuthorMapper.php'),
            \file_get_contents($dir.'/Mapper/AuthorMapper.php')
        );
        $this->assertEquals(
            \file_get_contents($fixturesDir.'/BlogPost.php'),
            \file_get_contents($dir.'/BlogPost.php')
        );
        $this->assertEquals(
            \file_get_contents($fixturesDir.'/Mapper/BlogPostMapper.php'),
            \file_get_contents($dir.'/Mapper/BlogPostMapper.php')
        );

        // Small cleanup
        (new Filesystem())->remove($dir);

        return $fixturesDir;
    }

    /**
     * @depends testCodeGeneration
     * @vcr e2e_code_generation_create_delete.json
     *
     * @param string $fixturesDir
     */
    public function testGeneratedClassesWork(string $fixturesDir)
    {
        $client = $this->getClient($this->codeGenerationSpaceId);
        $builder = $client->getBuilder();

        require $fixturesDir.'/_loader.php';

        $author = new Author();
        $author->setName('en-US', 'Lee Adama');
        $author->setLocation('en-US', ['lat' => 50, 'lon' => 50]);
        $author->setIsActive('en-US', true);
        $author->setMisc('en-US', ['codename' => 'Apollo']);
        $author->setPicture('en-US', new Link('24jR8tPh6cWyQyWecs8USO', 'Asset'));
        $client->entry->create($author);
        $this->assertNotNull($author->getId());
        $this->assertEquals('Lee Adama', $author->getName('en-US'));
        $this->assertEquals(['lat' => 50, 'lon' => 50], $author->getLocation('en-US'));
        $this->assertTrue($author->getIsActive('en-US'));
        $this->assertEquals(['codename' => 'Apollo'], $author->getMisc('en-US'));
        $picture = $author->resolvePictureLink('en-US');
        $this->assertInstanceOf(Asset::class, $picture);
        $this->assertEquals('Lee Adama', $picture->getTitle('en-US'));

        $blogPost = new BlogPost();
        $blogPost->setTitle('en-US', 'How to survive the deep space');
        $blogPost->setBody('en-US', 'You can\t.');
        $blogPost->setPublishedAt('en-US', new ApiDateTime('2017-10-06T11:30:00'));
        $blogPost->setImage('en-US', new Link('28Yop3scFS8U8EyWwOIoiy', 'Asset'));
        $blogPost->setRelated('en-US', []);
        $blogPost->setTags('en-US', ['space', 'survival']);
        $blogPost->setAuthor('en-US', $author->asLink());
        $client->entry->create($blogPost);
        $this->assertNotNull($blogPost->getId());
        $this->assertEquals('How to survive the deep space', $blogPost->getTitle('en-US'));
        $this->assertEquals('You can\t.', $blogPost->getBody('en-US'));
        $this->assertEquals(new ApiDateTime('2017-10-06T11:30:00'), $blogPost->getPublishedAt('en-US'));
        $image = $blogPost->resolveImageLink('en-US');
        $this->assertInstanceOf(Asset::class, $image);
        $this->assertEquals('Space', $image->getTitle('en-US'));
        $this->assertNull($blogPost->getRelated('en-US'));
        $this->assertEquals(['space', 'survival'], $blogPost->getTags('en-US'));

        $blogPost->delete();
        $author->delete();
    }
}
