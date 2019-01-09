<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
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

    /**
     * @param array $data
     */
    protected function initEnvironment(array $data)
    {
        $this->environment = new Link($data['environment']['sys']['id'], $data['environment']['sys']['linkType']);
    }

    /**
     * @return array
     */
    protected function jsonSerializeEnvironment(): array
    {
        return [
            'environment' => $this->environment->jsonSerialize(),
        ];
    }

    /**
     * @return Link
     */
    public function getEnvironment(): Link
    {
        return $this->environment;
    }
}
