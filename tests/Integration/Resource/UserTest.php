<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\Unit\Resource;

use Contentful\Management\ResourceBuilder;
use Contentful\Management\Resource\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testInvalidCreation()
    {
        new User();
    }

    public function testJsonSerialize()
    {
        $user = (new ResourceBuilder())->buildObjectsFromRawData([
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

        $json = '{"sys":{"type":"User"},"firstName":"Titus","lastName":"Andromedon","avatarUrl":"https://www.example.com/avatar.jpg","email":"pinotnoir@example.com","activated":true,"signInCount":10,"confirmed":true}';

        $this->assertJsonStringEqualsJsonString($json, json_encode($user));
    }
}
