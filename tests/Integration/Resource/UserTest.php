<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Integration\Resource;

use Contentful\Management\Resource\User;
use Contentful\Management\ResourceBuilder;
use Contentful\Tests\Management\BaseTestCase;

class UserTest extends BaseTestCase
{
    /**
     * @expectedException \Error
     */
    public function testInvalidCreation()
    {
        new User();
    }

    /**
     * This test is meaningless.
     * It's only here because private, empty constructors are not correctly picked by code coverage.
     */
    public function testConstructor()
    {
        $class = new \ReflectionClass(User::class);
        $object = $class->newInstanceWithoutConstructor();
        $constructor = $class->getConstructor();
        $constructor->setAccessible(\true);
        $constructor->invoke($object);

        $this->markTestAsPassed();
    }

    /**
     * @return User
     */
    public function testJsonSerialize(): User
    {
        $resource = (new ResourceBuilder())
            ->build($this->getParsedFixture('serialize.json'))
        ;

        $this->assertJsonFixtureEqualsJsonObject('Integration/Resource/User/serialize.json', $resource);

        return $resource;
    }

    /**
     * @param User $user
     *
     * @depends testJsonSerialize
     * @expectedException \LogicException
     * @expectedExceptionMessage Trying to update resource object in mapper of type "Contentful\Management\Mapper\User", but only creation from scratch is supported.
     */
    public function testInvalidUpdate(User $user)
    {
        (new ResourceBuilder())
            ->build(['sys' => [
                'type' => 'User',
            ]], $user)
        ;
    }

    /**
     * @param User $user
     *
     * @depends testJsonSerialize
     * @expectedException \LogicException
     * @expectedExceptionMessage Trying to convert object of class "Contentful\Management\Resource\User" to a request body format, but operation is not supported on this class.
     */
    public function testInvalidConversionToRequestBody(User $user)
    {
        $user->asRequestBody();
    }
}
