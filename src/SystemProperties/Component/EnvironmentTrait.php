<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties\Component;

use Contentful\Core\Api\Link;

trait EnvironmentTrait
{
    /**
     * @var Link
     */
    protected $environment;

    protected function initEnvironment(array $data)
    {
        $this->environment = new Link($data['environment']['sys']['id'], $data['environment']['sys']['linkType']);
    }

    protected function jsonSerializeEnvironment(): array
    {
        return [
            'environment' => $this->environment->jsonSerialize(),
        ];
    }

    public function getEnvironment(): Link
    {
        return $this->environment;
    }
}
