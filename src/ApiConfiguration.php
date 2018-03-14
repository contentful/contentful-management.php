<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management;

use Contentful\Management\Resource\ResourceInterface;

/**
 * ApiConfiguration class.
 */
class ApiConfiguration
{
    /**
     * This array includes the configuration necessary for handling API calls for every resource type.
     *
     * Each entry includes the following values.
     * "uri": The URI, with placeholder parameters, which identifies the resource.
     * "parameters": A list of required parameters for building the URI.
     * "id": The name of the placeholder parameter which represents the actual resource ID.
     * "baseUri": Optionally, the host that is used for a specific endpoint.
     *
     * @var array
     */
    private static $configuration = [
        Resource\Asset::class => [
            'uri' => '/spaces/{space}/assets/{asset}',
            'parameters' => ['space'],
            'id' => 'asset',
        ],
        Resource\ContentType::class => [
            'uri' => '/spaces/{space}/content_types/{contentType}',
            'parameters' => ['space'],
            'id' => 'contentType',
        ],
        Resource\ContentTypeSnapshot::class => [
            'uri' => '/spaces/{space}/content_types/{contentType}/snapshots/{snapshot}',
            'parameters' => ['space', 'contentType'],
            'id' => 'snapshot',
        ],
        Resource\DeliveryApiKey::class => [
            'uri' => '/spaces/{space}/api_keys/{deliveryApiKey}',
            'parameters' => ['space'],
            'id' => 'deliveryApiKey',
        ],
        Resource\EditorInterface::class => [
            'uri' => '/spaces/{space}/content_types/{contentType}/editor_interface',
            'parameters' => ['space', 'contentType'],
            'id' => '',
        ],
        Resource\Entry::class => [
            'uri' => '/spaces/{space}/entries/{entry}',
            'parameters' => ['space'],
            'id' => 'entry',
        ],
        Resource\EntrySnapshot::class => [
            'uri' => '/spaces/{space}/entries/{entry}/snapshots/{snapshot}',
            'parameters' => ['space', 'entry'],
            'id' => 'snapshot',
        ],
        Resource\Extension::class => [
            'uri' => '/spaces/{space}/extensions/{extension}',
            'parameters' => ['space'],
            'id' => 'extension',
        ],
        Resource\Locale::class => [
            'uri' => '/spaces/{space}/locales/{locale}',
            'parameters' => ['space'],
            'id' => 'locale',
        ],
        Resource\Organization::class => [
            'uri' => '/organizations',
            'parameters' => [],
            'id' => '',
        ],
        Resource\PersonalAccessToken::class => [
            'uri' => 'users/me/access_tokens/{personalAccessToken}',
            'parameters' => [],
            'id' => 'personalAccessToken',
        ],
        Resource\PreviewApiKey::class => [
            'uri' => '/spaces/{space}/preview_api_keys/{previewApiKey}',
            'parameters' => ['space'],
            'id' => 'previewApiKey',
        ],
        Resource\Role::class => [
            'uri' => '/spaces/{space}/roles/{role}',
            'parameters' => ['space'],
            'id' => 'role',
        ],
        Resource\Space::class => [
            'uri' => '/spaces/{space}',
            'parameters' => [],
            'id' => 'space',
        ],
        Resource\SpaceMembership::class => [
            'uri' => '/spaces/{space}/space_memberships/{spaceMembership}',
            'parameters' => ['space'],
            'id' => 'spaceMembership',
        ],
        Resource\Upload::class => [
            'uri' => '/spaces/{space}/uploads/{upload}',
            'baseUri' => 'https://upload.contentful.com',
            'parameters' => ['space'],
            'id' => 'upload',
        ],
        Resource\User::class => [
            'uri' => '/users/{user}',
            'parameters' => [],
            'id' => '',
        ],
        Resource\Webhook::class => [
            'uri' => '/spaces/{space}/webhook_definitions/{webhook}',
            'parameters' => ['space'],
            'id' => 'webhook',
        ],
        Resource\WebhookCall::class => [
            'uri' => '/spaces/{space}/webhooks/{webhook}/calls/{call}',
            'parameters' => ['space', 'webhook'],
            'id' => 'call',
        ],
        Resource\WebhookHealth::class => [
            'uri' => '/spaces/{space}/webhooks/{webhook}/health',
            'parameters' => ['space', 'webhook'],
            'id' => '',
        ],
    ];

    /**
     * Returns the configuration for a specific class.
     *
     * @param string|ResourceInterface $resource Either the FQCN of a resource, or an object implementing ResourceInterface
     *
     * @throws \InvalidArgumentException When passing an invalid or unrecognized resource
     *
     * @return array
     */
    public function getConfigFor($resource): array
    {
        if (\is_object($resource) && !($resource instanceof ResourceInterface)) {
            throw new \InvalidArgumentException(\sprintf(
                'Trying to get configuration for an object of class "%s" which does not implement ResourceInterface.',
                \get_class($resource)
            ));
        }

        $class = \is_object($resource) ? \get_class($resource) : $resource;
        if ('\\' === \mb_substr($class, 0, 1)) {
            $class = \mb_substr($class, 1);
        }

        if (isset(self::$configuration[$class])) {
            return self::$configuration[$class];
        }

        foreach (\class_parents($class) as $parent) {
            if (isset(self::$configuration[$parent])) {
                return self::$configuration[$parent];
            }
        }

        throw new \InvalidArgumentException(\sprintf(
            'Trying to access invalid configuration for class "%s".',
            $class
        ));
    }
}