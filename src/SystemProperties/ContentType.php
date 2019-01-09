<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties;

class ContentType extends BaseSystemProperties implements VersionableSystemPropertiesInterface
{
    use Component\ArchivedTrait,
        Component\EnvironmentTrait,
        Component\PublishedTrait,
        Component\SpaceTrait;

    /**
     * ContentType constructor.
     *
     * @param array $sys
     */
    public function __construct(array $sys)
    {
        $this->init('ContentType', $sys['id'] ?? '');

        $this->initArchived($sys);
        $this->initEnvironment($sys);
        $this->initPublished($sys);
        $this->initSpace($sys);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return \array_merge(
            parent::jsonSerialize(),
            $this->jsonSerializeArchived(),
            $this->jsonSerializeEnvironment(),
            $this->jsonSerializePublished(),
            $this->jsonSerializeSpace()
        );
    }
}
