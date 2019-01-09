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

trait SpaceTrait
{
    /**
     * @var Link
     */
    protected $space;

    /**
     * @param array $data
     */
    protected function initSpace(array $data)
    {
        $this->space = new Link($data['space']['sys']['id'], $data['space']['sys']['linkType']);
    }

    /**
     * @return array
     */
    protected function jsonSerializeSpace(): array
    {
        return [
            'space' => $this->space->jsonSerialize(),
        ];
    }

    /**
     * @return Link
     */
    public function getSpace(): Link
    {
        return $this->space;
    }
}
