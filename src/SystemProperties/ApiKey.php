<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties;

class ApiKey extends BaseSystemProperties
{
    use Component\EditedTrait,
        Component\SpaceTrait;

    /**
     * ApiKey constructor.
     *
     * @param array $sys
     */
    public function __construct(array $sys)
    {
        $this->init($sys['type'] ?? 'ApiKey', $sys['id'] ?? '');

        $this->initEdited($sys);
        $this->initSpace($sys);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return \array_merge(
            parent::jsonSerialize(),
            $this->jsonSerializeEdited(),
            $this->jsonSerializeSpace()
        );
    }
}
