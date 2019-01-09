<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties;

class Upload extends BaseSystemProperties
{
    use Component\CreatedTrait,
        Component\ExpiredTrait,
        Component\SpaceTrait;

    /**
     * Upload constructor.
     *
     * @param array $sys
     */
    public function __construct(array $sys)
    {
        $this->init('Upload', $sys['id'] ?? '');

        $this->initCreated($sys);
        $this->initExpired($sys);
        $this->initSpace($sys);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return \array_merge(
            parent::jsonSerialize(),
            $this->jsonSerializeCreated(),
            $this->jsonSerializeExpired(),
            $this->jsonSerializeSpace()
        );
    }
}
