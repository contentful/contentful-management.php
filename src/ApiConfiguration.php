<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
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
     * Published content types are an edge case.
     * All resources are supposed to have one endpoint representation in the API,
     * but content types can be present in their normal form, and in the form
     * they have been last published as. Because of this, in order to not to duplicate
     * the content type resource, we create a virtual resource for read-only operations.
     *
     * @var string
     */
    const PUBLISHED_CONTENT_TYPE_RESOURCE = 'Contentful\\Management\\Resource\\PublishedContentType';

    /**
     * This array includes the configuration necessary for handling API calls for every resource type.
     *
     * Each entry includes the following values.
     * "uri": The URI, with placeholder parameters, which identifies the resource.
     * "parameters": A list of required parameters for building the URI.
     * "id": The name of the placeholder parameter which represents the actual resource ID.
     * "host": Optionally, the host that is used for a specific endpoint.
     *
     * @var array
     */
    private static $configuration = [
        Resource\Asset::class => [
            'uri' => '/spaces/{space}/environments/{environment}/assets/{asset}',
            'parameters' => ['space', 'environment'],
            'id' => 'asset',
        ],
        Resource\ContentType::class => [
            'uri' => '/spaces/{space}/environments/{environment}/content_types/{contentType}',
            'parameters' => ['space', 'environment'],
            'id' => 'contentType',
        ],
        self::PUBLISHED_CONTENT_TYPE_RESOURCE => [
            'uri' => '/spaces/{space}/environments/{environment}/public/content_types/{contentType}',
            'parameters' => ['space', 'environment'],
            'id' => 'contentType',
        ],
        Resource\ContentTypeSnapshot::class => [
            'uri' => '/spaces/{space}/environments/{environment}/content_types/{contentType}/snapshots/{snapshot}',
            'parameters' => ['space', 'contentType', 'environment'],
            'id' => 'snapshot',
        ],
        Resource\DeliveryApiKey::class => [
            'uri' => '/spaces/{space}/api_keys/{deliveryApiKey}',
            'parameters' => ['space'],
            'id' => 'deliveryApiKey',
        ],
        Resource\EditorInterface::class => [
            'uri' => '/spaces/{space}/environments/{environment}/content_types/{contentType}/editor_interface',
            'parameters' => ['space', 'contentType', 'environment'],
            'id' => '',
        ],
        Resource\Entry::class => [
            'uri' => '/spaces/{space}/environments/{environment}/entries/{entry}',
            'parameters' => ['space', 'environment'],
            'id' => 'entry',
        ],
        Resource\EntrySnapshot::class => [
            'uri' => '/spaces/{space}/environments/{environment}/entries/{entry}/snapshots/{snapshot}',
            'parameters' => ['space', 'entry', 'environment'],
            'id' => 'snapshot',
        ],
        Resource\Environment::class => [
            'uri' => '/spaces/{space}/environments/{environment}',
            'parameters' => ['space'],
            'id' => 'environment',
        ],
        Resource\Extension::class => [
            'uri' => '/spaces/{space}/environments/{environment}/extensions/{extension}',
            'parameters' => ['space', 'environment'],
            'id' => 'extension',
        ],
        Resource\Locale::class => [
            'uri' => '/spaces/{space}/environments/{environment}/locales/{locale}',
            'parameters' => ['space', 'environment'],
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
            'host' => 'https://upload.contentful.com',
            'parameters' => ['space'],
            'id' => 'upload',
        ],
        Resource\User::class => [
            'uri' => '/users/me',
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
     * This array defines the configuration for mapping
     * a Contentful type to a specific SDK resource.
     *
     * @var array<string, string>
     */
    private static $linkMap = [
        'Asset' => Resource\Asset::class,
        'ContentType' => Resource\ContentType::class,
        'Entry' => Resource\Entry::class,
        'Environment' => Resource\Environment::class,
        'PreviewApiKey' => Resource\PreviewApiKey::class,
        'Role' => Resource\Role::class,
        'Space' => Resource\Space::class,
        'Upload' => Resource\Upload::class,
        'WebhookDefinition' => Resource\Webhook::class,
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
        $class = \is_object($resource) ? \get_class($resource) : $resource;
        if ('\\' === \mb_substr($class, 0, 1)) {
            $class = \mb_substr($class, 1);
        }

        if (isset(self::$configuration[$class])) {
            return \array_merge(['class' => $class], self::$configuration[$class]);
        }

        foreach (\class_parents($class) as $parent) {
            if (isset(self::$configuration[$parent])) {
                return \array_merge(['class' => $class], self::$configuration[$parent]);
            }
        }

        throw new \InvalidArgumentException(\sprintf(
            'Trying to access invalid configuration for class "%s".',
            $class
        ));
    }

    /**
     * Returns the configuration for a specific link type.
     *
     * @param string $linkType
     *
     * @throws \InvalidArgumentException When passing an unrecognized link type
     *
     * @return array
     */
    public function getLinkConfigFor(string $linkType): array
    {
        if (!isset(self::$linkMap[$linkType])) {
            throw new \InvalidArgumentException(\sprintf(
                'Trying to get link configuration for an invalid link type "%s".',
                $linkType
            ));
        }

        $class = self::$linkMap[$linkType];
        $config = $this->getConfigFor($class);
        $config['class'] = $class;

        return $config;
    }
}
