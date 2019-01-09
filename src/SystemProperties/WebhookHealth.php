<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties;

class WebhookHealth extends BaseSystemProperties
{
    use Component\CreatedByTrait,
        Component\SpaceTrait;

    /**
     * WebhookHealth constructor.
     *
     * @param array $sys
     */
    public function __construct(array $sys)
    {
        $this->init('Webhook', $sys['id'] ?? '');

        $this->initCreatedBy($sys);
        $this->initSpace($sys);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return \array_merge(
            parent::jsonSerialize(),
            $this->jsonSerializeCreatedBy(),
            $this->jsonSerializeSpace()
        );
    }
}
