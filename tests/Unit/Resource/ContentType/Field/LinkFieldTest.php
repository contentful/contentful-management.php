<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Unit\Resource\ContentType\Field;

use Contentful\Management\Resource\ContentType\Field\LinkField;
use Contentful\Tests\Management\BaseTestCase;

class LinkFieldTest extends BaseTestCase
{
    public function testEntryLinkGetSetData()
    {
        $field = new LinkField('bestFriend', 'Best Friend', 'Entry');

        $this->assertSame('Entry', $field->getLinkType());

        $field->setLinkType('Asset');
        $this->assertSame('Asset', $field->getLinkType());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Invalid link type "Invalid". Valid values are Asset, Entry.
     */
    public function testConstructorInvalidLinkType()
    {
        new LinkField('bestFriend', 'Best Friend', 'Invalid');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Invalid link type "Invalid". Valid values are Asset, Entry.
     */
    public function testSetterInvalidLinkType()
    {
        $field = new LinkField('bestFriend', 'Best Friend', 'Asset');

        $field->setLinkType('Invalid');
    }
}
