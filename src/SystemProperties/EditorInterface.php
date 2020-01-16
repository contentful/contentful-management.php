<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties;

class EditorInterface extends BaseSystemProperties implements VersionableSystemPropertiesInterface
{
    use Component\ContentTypeTrait;
    use
        Component\EditedTrait;
    use
        Component\EnvironmentTrait;
    use
        Component\SpaceTrait;

    /**
     * EditorInterface constructor.
     */
    public function __construct(array $sys)
    {
        $this->init('EditorInterface', $sys['id'] ?? '');

        $this->initContentType($sys);
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
            $this->jsonSerializeContentType(),
            $this->jsonSerializeEdited(),
            $this->jsonSerializeEnvironment(),
            $this->jsonSerializeSpace()
        );
    }
}
