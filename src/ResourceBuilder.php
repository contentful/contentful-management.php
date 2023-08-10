<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2023 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management;

use Contentful\Core\ResourceBuilder\BaseResourceBuilder;

/**
 * ResourceBuilder class.
 *
 * This class is responsible for populating PHP objects
 * using data received from Contentful's API.
 */
class ResourceBuilder extends BaseResourceBuilder
{
    /**
     * {@inheritdoc}
     */
    protected function getMapperNamespace()
    {
        return __NAMESPACE__.'\\Mapper';
    }

    /**
     * {@inheritdoc}
     */
    protected function createMapper($fqcn)
    {
        return new $fqcn($this);
    }

    /**
     * {@inheritdoc}
     */
    protected function getSystemType(array $data): string
    {
        switch ($data['sys']['type']) {
            case 'Array':
                if (isset($data['includes'])) {
                    return 'ResourceReferences';
                }
                return 'ResourceArray';

            case 'Asset':
            case 'ContentType':
            case 'EditorInterface':
            case 'Entry':
            case 'Environment':
            case 'Extension':
            case 'Locale':
            case 'Organization':
            case 'Role':
            case 'PersonalAccessToken':
            case 'PreviewApiKey':
            case 'Space':
            case 'SpaceMembership':
            case 'Upload':
            case 'User':
                return $data['sys']['type'];

            case 'Snapshot':
                return $data['sys']['snapshotEntityType'].'Snapshot';

            case 'ApiKey':
                return 'DeliveryApiKey';
            case 'Webhook':
                return 'WebhookHealth';
            case 'WebhookCallDetails':
            case 'WebhookCallOverview':
                return 'WebhookCall';
            case 'WebhookDefinition':
                return 'Webhook';
        }

        throw new \InvalidArgumentException(\sprintf('Unexpected system type "%s" while trying to build a resource.', $data['sys']['type']));
    }
}
