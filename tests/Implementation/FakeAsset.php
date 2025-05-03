<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Implementation;

use Contentful\Management\Resource\Asset;
use Contentful\Management\SystemProperties\Asset as SystemProperties;

class FakeAsset extends Asset
{
    public function __construct(string $id, string $spaceId)
    {
        $this->sys = new SystemProperties([
            'type' => 'Asset',
            'id' => $id,
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
            'version' => 23,
            'space' => [
                'sys' => [
                    'type' => 'Link',
                    'linkType' => 'Space',
                    'id' => $spaceId,
                ],
            ],
            'environment' => [
                'sys' => [
                    'type' => 'Link',
                    'linkType' => 'Environment',
                    'id' => 'master',
                ],
            ],
        ]);
    }
}
