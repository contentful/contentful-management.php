<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties;

class WebhookCall extends BaseSystemProperties
{
    use Component\CreatedTrait,
        Component\SpaceTrait;

    /**
     * WebhookCall constructor.
     *
     * @param array $sys
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
