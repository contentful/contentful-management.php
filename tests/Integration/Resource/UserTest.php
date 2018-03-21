<?php

/**
 * This file is part of the contentful-management.php package.
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
     * @expectedException \LogicException
     * @expectedExceptionMessage Class "Contentful\Management\Resource\User" can only be instantiated as a result of an API call, manual creation is not allowed.
     */
    public function testInvalidCreation()
    {
        new User();
    }

    /**
     * @return User
     */
    public function testJsonSerialize(): User
    {
        $user = (new ResourceBuilder())->build([
            'sys' => [
                'type' => 'User',
            ],
            'firstName' => 'Titus',
            'lastName' => 'Andromedon',
            'avatarUrl' => 'https://www.example.com/avatar.jpg',
            'email' => 'pinotnoir@example.com',
            'activated' => true,
            'signInCount' => 10,
            'confirmed' => true,
        ]);

        $this->assertJsonFixtureEqualsJsonObject('Integration/Resource/user.json', $user);

        return $user;
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
            ]], $user);
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
