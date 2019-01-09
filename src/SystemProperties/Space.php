<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties;

class Space extends BaseSystemProperties implements VersionableSystemPropertiesInterface
{
    use Component\EditedTrait;

    /**
     * Space constructor.
     *
     * @param array $sys
     */
    public function __construct(array $sys)
    {
        $this->init('Space', $sys['id'] ?? '');

        $this->initEdited($sys);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return \array_merge(
            parent::jsonSerialize(),
            $this->jsonSerializeEdited()
        );
    }
}
