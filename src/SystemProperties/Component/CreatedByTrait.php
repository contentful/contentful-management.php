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

trait CreatedByTrait
{
    /**
     * @var Link
     */
    protected $createdBy;

    protected function initCreatedBy(array $data)
    {
        if (isset($data['createdBy'])) {
            $this->createdBy = new Link($data['createdBy']['sys']['id'], $data['createdBy']['sys']['linkType']);
        }
    }

    protected function jsonSerializeCreatedBy(): array
    {
        return [
            'createdBy' => $this->createdBy->jsonSerialize(),
        ];
    }

    public function getCreatedBy(): Link
    {
        return $this->createdBy;
    }
}
