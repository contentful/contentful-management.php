<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\E2E;

use Contentful\Link;
use Contentful\Tests\End2EndTestCase;

class UserTest extends End2EndTestCase
{
    /**
     * @vcr e2e_user_get_me.json
     */
    public function testGetOwnUser()
    {
        $user = $this->client->getOwnUser();

        $this->assertEquals('PHP SDK', $user->getFirstName());
        $this->assertEquals('Tests', $user->getLastName());
        $this->assertEquals('cidevdocs+php@contentful.com', $user->getEmail());
        $this->assertEquals('https://www.gravatar.com/avatar/6474b043cb2a58f34b0576ccf83d56e2?s=50&d=https%3A%2F%2Fstatic.contentful.com%2Fgatekeeper%2Fusers%2Fdefault-43783205a36955c723acfe0a32bcf72eebe709cac2067249bc80385b78ccc70d.png', $user->getAvatarUrl());
        $this->assertEquals(true, $user->isActivated());
        $this->assertEquals(true, $user->isConfirmed());
        $this->assertInternalType('integer', $user->getSignInCount());
        $this->assertGreaterThan(1, $user->getSignInCount());
        $this->assertEquals('4Q3e6duhma7V6czH7UXHzE', $user->getSystemProperties()->getId());
        $this->assertEquals(new Link('4Q3e6duhma7V6czH7UXHzE', 'User'), $user->asLink());
        $this->assertEquals('User', $user->getSystemProperties()->getType());
    }
}
