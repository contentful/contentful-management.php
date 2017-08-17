<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Unit\Field;

use Contentful\Management\Field\LinkField;
use PHPUnit\Framework\TestCase;

class LinkFieldTest extends TestCase
{
    public function testEntryLinkGetSetData()
    {
        $field = new LinkField('bestFriend', 'Best Friend', 'Entry');

        $this->assertEquals('Entry', $field->getLinkType());

        $field->setLinkType('Asset');
        $this->assertEquals('Asset', $field->getLinkType());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Invalid link type Invalid. Valid values are Asset, Entry.
     */
    public function testConstructorInvalidLinkType()
    {
        new LinkField('bestFriend', 'Best Friend', 'Invalid');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Invalid link type Invalid. Valid values are Asset, Entry.
     */
    public function testSetterInvalidLinkType()
    {
        $field = new LinkField('bestFriend', 'Best Friend', 'Asset');

        $field->setLinkType('Invalid');
    }
}
