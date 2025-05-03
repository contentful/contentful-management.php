<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Implementation;

use Contentful\Management\SystemProperties\BaseSystemProperties;

class MockSystemProperties extends BaseSystemProperties
{
    public function __construct(array $data)
    {
        $this->init($data['type'] ?? 'Entry', $data['id'] ?? '');
    }
}
