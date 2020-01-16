<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties;

class Snapshot extends BaseSystemProperties
{
    use Component\EnvironmentTrait;
    use
        Component\CreatedTrait;
    use
        Component\SnapshotTrait;
    use
        Component\SpaceTrait;
    use
        Component\UpdatedTrait;

    /**
     * Snapshot constructor.
     */
    public function __construct(array $sys)
    {
        $this->init('Snapshot', $sys['id'] ?? '');

        $this->initCreated($sys);
        $this->initEnvironment($sys);
        $this->initSnapshot($sys);
        $this->initSpace($sys);
        $this->initUpdated($sys);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return \array_merge(
            parent::jsonSerialize(),
            $this->jsonSerializeCreated(),
            $this->jsonSerializeEnvironment(),
            $this->jsonSerializeSnapshot(),
            $this->jsonSerializeSpace(),
            $this->jsonSerializeUpdated()
        );
    }
}
