<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties;

class SpaceMembership extends BaseSystemProperties implements VersionableSystemPropertiesInterface
{
    use Component\EditedTrait;
    use
        Component\SpaceTrait;

    /**
     * SpaceMembership constructor.
     */
    public function __construct(array $sys)
    {
        $this->init('SpaceMembership', $sys['id'] ?? '');

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
