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

trait StatusTrait
{
    /**
     * @var Link
     */
    protected $status;

    /**
     * @param array $data
     */
    protected function initStatus(array $data)
    {
        $this->status = new Link($data['status']['sys']['id'], $data['status']['sys']['linkType']);
    }

    /**
     * @return array
     */
    protected function jsonSerializeStatus(): array
    {
        return [
            'status' => $this->status->jsonSerialize(),
        ];
    }

    /**
     * @return Link
     */
    public function getStatus(): Link
    {
        return $this->status;
    }
}
