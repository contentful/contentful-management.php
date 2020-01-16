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

trait ContentTypeTrait
{
    /**
     * @var Link
     */
    protected $contentType;

    protected function initContentType(array $data)
    {
        $this->contentType = new Link($data['contentType']['sys']['id'], $data['contentType']['sys']['linkType']);
    }

    protected function jsonSerializeContentType(): array
    {
        return [
            'contentType' => $this->contentType->jsonSerialize(),
        ];
    }

    public function getContentType(): Link
    {
        return $this->contentType;
    }
}
