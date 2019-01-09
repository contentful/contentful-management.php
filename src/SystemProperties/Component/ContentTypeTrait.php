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

trait ContentTypeTrait
{
    /**
     * @var Link
     */
    protected $contentType;

    /**
     * @param array $data
     */
    protected function initContentType(array $data)
    {
        $this->contentType = new Link($data['contentType']['sys']['id'], $data['contentType']['sys']['linkType']);
    }

    /**
     * @return array
     */
    protected function jsonSerializeContentType(): array
    {
        return [
            'contentType' => $this->contentType->jsonSerialize(),
        ];
    }

    /**
     * @return Link
     */
    public function getContentType(): Link
    {
        return $this->contentType;
    }
}
