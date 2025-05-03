<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource;

use Contentful\Management\Resource\Environment;
use Contentful\Tests\Management\BaseTestCase;

class EnvironmentTest extends BaseTestCase
{
    public function testGetSetData()
    {
        $environment = new Environment('CI Environment');

        $this->assertSame('CI Environment', $environment->getName());

        $environment->setName('QA Environment');
        $this->assertSame('QA Environment', $environment->getName());
    }

    public function testJsonSerialize()
    {
        $environment = new Environment('CI Environment');

        $this->assertJsonFixtureEqualsJsonObject('Unit/Resource/environment.json', $environment);
    }
}
