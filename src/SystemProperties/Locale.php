<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties;

class Locale extends BaseSystemProperties implements VersionableSystemPropertiesInterface
{
    use Component\EditedTrait,
        Component\EnvironmentTrait,
        Component\SpaceTrait;

    /**
     * Locale constructor.
     *
     * @param array $sys
     */
    public function __construct(array $sys)
    {
        $this->init('Locale', $sys['id'] ?? '');

        $this->initEdited($sys);
        $this->initEnvironment($sys);
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
            $this->jsonSerializeEnvironment(),
            $this->jsonSerializeSpace()
        );
    }
}
