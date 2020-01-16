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

trait UpdatedByTrait
{
    /**
     * @var Link
     */
    protected $updatedBy;

    protected function initUpdatedBy(array $data)
    {
        if (isset($data['createdBy'])) {
            $this->updatedBy = new Link($data['updatedBy']['sys']['id'], $data['updatedBy']['sys']['linkType']);
        }
    }

    protected function jsonSerializeUpdatedBy(): array
    {
        return [
            'updatedBy' => $this->updatedBy->jsonSerialize(),
        ];
    }

    public function getUpdatedBy(): Link
    {
        return $this->updatedBy;
    }
}
