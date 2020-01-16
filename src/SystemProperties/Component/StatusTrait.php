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

trait StatusTrait
{
    /**
     * @var Link
     */
    protected $status;

    protected function initStatus(array $data)
    {
        $this->status = new Link($data['status']['sys']['id'], $data['status']['sys']['linkType']);
    }

    protected function jsonSerializeStatus(): array
    {
        return [
            'status' => $this->status->jsonSerialize(),
        ];
    }

    public function getStatus(): Link
    {
        return $this->status;
    }
}
