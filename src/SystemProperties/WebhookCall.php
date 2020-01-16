<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties;

class WebhookCall extends BaseSystemProperties
{
    use Component\CreatedTrait;
    use
        Component\SpaceTrait;

    /**
     * WebhookCall constructor.
     */
    public function __construct(array $sys)
    {
        $this->init($sys['type'] ?? 'WebhookCallDetails', $sys['id'] ?? '');

        $this->initCreated($sys);
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
            $this->jsonSerializeSpace()
        );
    }
}
