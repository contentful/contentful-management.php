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

trait UpdatedByTrait
{
    /**
     * @var Link
     */
    protected $updatedBy;

    /**
     * @param array $data
     */
    protected function initUpdatedBy(array $data)
    {
        if (isset($data['createdBy'])) {
            $this->updatedBy = new Link($data['updatedBy']['sys']['id'], $data['updatedBy']['sys']['linkType']);
        }
    }

    /**
     * @return array
     */
    protected function jsonSerializeUpdatedBy(): array
    {
        return [
            'updatedBy' => $this->updatedBy->jsonSerialize(),
        ];
    }

    /**
     * @return Link
     */
    public function getUpdatedBy(): Link
    {
        return $this->updatedBy;
    }
}
