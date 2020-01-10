<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties;

class Locale extends BaseSystemProperties implements VersionableSystemPropertiesInterface
{
    use Component\EditedTrait;
    use
        Component\EnvironmentTrait;
    use
        Component\SpaceTrait;

    /**
     * Locale constructor.
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
