<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

use function GuzzleHttp\json_encode;
use Contentful\Link;
use Contentful\Client as BaseClient;
use Contentful\Management\Resource\PersonalAccessToken;
use Contentful\Management\Resource\Space;
use Contentful\Management\Resource\User;
use Contentful\ResourceArray;
use Contentful\Management\Resource\ResourceInterface;

/**
 * Client class.
 *
 * This class is responsible for querying Contentful's API,
 * and for lower level operations such as space management.
 */
class Client extends BaseClient
{
    /**
     * @var string
     */
    const VERSION = '0.7.0-dev';

    /**
     * @var string
     */
    const URI_MANAGEMENT = 'https://api.contentful.com';

    /**
     * @var string
     */
    const URI_UPLOAD = 'https://upload.contentful.com';

    /**
     * @var ResourceBuilder
     */
    private $builder;

    /**
     * @var SpaceManager[]
     */
    private $spaceManagers = [];

    /**
     * Client constructor.
     *
     * @param string $token
     * @param array  $options
     */
    public function __construct(string $token, array $options = [])
    {
        $api = 'MANAGEMENT';
        $guzzle = $options['guzzle'] ?? null;
        $logger = $options['logger'] ?? null;
        $baseUri = $options['uriOverride'] ?? self::URI_MANAGEMENT;

        parent::__construct($token, $baseUri, $api, $logger, $guzzle);

        $this->builder = new ResourceBuilder();
    }

    /**
     * @return ResourceBuilder
     */
    public function getBuilder(): ResourceBuilder
    {
        return $this->builder;
    }

    /**
     * @param ResourceBuilder $builder
     *
     * @return $this
     */
    public function setBuilder(ResourceBuilder $builder)
    {
        $this->builder = $builder;

        return $this;
    }

    /**
     * @param string $spaceId
     *
     * @return SpaceManager
     */
    public function getSpaceManager(string $spaceId): SpaceManager
    {
        if (!isset($this->spaceManagers[$spaceId])) {
            $this->spaceManagers[$spaceId] = new SpaceManager($this, $this->builder, $spaceId);
        }

        return $this->spaceManagers[$spaceId];
    }

    /**
     * {@inheritdoc}
     */
    public function request($method, $path, array $options = [])
    {
        return parent::request($method, $path, $options);
    }

    /**
     * Makes a GET call to an API endpoint,
     * and returns the built object.
     *
     * @param string     $path
     * @param Query|null $query
     * @param array      $options
     *
     * @return ResourceArray|ResourceInterface
     */
    public function get(string $path, Query $query = null, $options = [])
    {
        $options['query'] = $query !== null
            ? $query->getQueryData()
            : [];

        $response = $this->request('GET', $path, $options);

        return $this->builder->build($response);
    }

    /**
     * Resolves a Link object to the actual resource.
     *
     * @param Link        $link
     * @param string|null $spaceId
     *
     * @return ResourceInterface
     */
    public function resolveLink(Link $link, string $spaceId = null): ResourceInterface
    {
        if ($link->getLinkType() == 'Space') {
            return $this->getSpace($link->getId());
        }

        if ($spaceId === null) {
            throw new \LogicException(
                'Trying to resolve a link of a resource that is bound to a space, but no "$spaceId" parameter is given.'
            );
        }

        $spaceManager = $this->getSpaceManager($spaceId);

        switch ($link->getLinkType()) {
            case 'Asset':
                return $spaceManager->getAsset($link->getId());
            case 'ContentType':
                return $spaceManager->getContentType($link->getId());
            case 'Entry':
                return $spaceManager->getEntry($link->getId());
            case 'PreviewApiKey':
                return $spaceManager->getPreviewApiKey($link->getId());
            case 'Role':
                return $spaceManager->getRole($link->getId());
            case 'Upload':
                return $spaceManager->getUpload($link->getId());
            case 'WebhookDefinition':
                return $spaceManager->getWebhook($link->getId());
        }

        throw new \InvalidArgumentException(sprintf(
            'Unexpected system type "%s" while trying to resolve a Link.',
            $link->getLinkType()
        ));
    }

    /**
     * @param string $spaceId
     *
     * @return Space
     */
    public function getSpace($spaceId): Space
    {
        return $this->get('spaces/'.$spaceId);
    }

    /**
     * @param Query|null $query
     *
     * @return ResourceArray
     */
    public function getSpaces(Query $query = null): ResourceArray
    {
        return $this->get('spaces', $query);
    }

    /**
     * @param Space       $space
     * @param string|null $organizationId
     * @param string      $defaultLocale
     */
    public function createSpace(Space $space, string $organizationId = null, string $defaultLocale = 'en-US')
    {
        $additionalHeaders = $organizationId ? ['X-Contentful-Organization' => $organizationId] : [];
        $bodyData = $this->prepareObjectForApi($space);

        if ($defaultLocale !== 'en-US') {
            $bodyData->defaultLocale = $defaultLocale;
        }
        $body = json_encode($bodyData, JSON_UNESCAPED_UNICODE);

        $response = $this->request('POST', 'spaces', [
            'additionalHeaders' => $additionalHeaders,
            'body' => $body,
        ]);

        $this->builder->build($response, $space);
    }

    /**
     * @param Space $space
     */
    public function updateSpace(Space $space)
    {
        $sys = $space->getSystemProperties();

        $body = json_encode($this->prepareObjectForApi($space), JSON_UNESCAPED_UNICODE);
        $additionalHeaders = ['X-Contentful-Version' => $sys->getVersion()];

        $response = $this->request('PUT', 'spaces/'.$sys->getId(), [
            'additionalHeaders' => $additionalHeaders,
            'body' => $body,
        ]);

        $this->builder->build($response, $space);
    }

    /**
     * @param Space $space
     */
    public function deleteSpace(Space $space)
    {
        $sys = $space->getSystemProperties();
        $this->request('DELETE', 'spaces/'.$sys->getId());
    }

    /**
     * @return User
     */
    public function getOwnUser(): User
    {
        return $this->get('users/me');
    }

    /**
     * @param string $personalAccessTokenId
     *
     * @return PersonalAccessToken
     */
    public function getPersonalAccessToken(string $personalAccessTokenId): PersonalAccessToken
    {
        return $this->get('users/me/access_tokens/'.$personalAccessTokenId);
    }

    /**
     * @param Query|null $query
     *
     * @return ResourceArray
     */
    public function getPersonalAccessTokens(Query $query = null): ResourceArray
    {
        return $this->get('users/me/access_tokens', $query);
    }

    /**
     * @param PersonalAccessToken $personalAccessToken
     */
    public function createPersonalAccessToken(PersonalAccessToken $personalAccessToken)
    {
        $options = [
            'body' => json_encode($this->prepareObjectForApi($personalAccessToken), JSON_UNESCAPED_UNICODE),
        ];

        $response = $this->request('POST', '/users/me/access_tokens', $options);

        $this->builder->build($response, $personalAccessToken);
    }

    /**
     * @param PersonalAccessToken $personalAccessToken
     */
    public function revokePersonalAccessToken(PersonalAccessToken $personalAccessToken)
    {
        $sys = $personalAccessToken->getSystemProperties();
        $response = $this->request('PUT', 'users/me/access_tokens/'.$sys->getId().'/revoked');

        $this->builder->build($response, $personalAccessToken);
    }

    /**
     * @param Query|null $query
     *
     * @return ResourceArray
     */
    public function getOrganizations(Query $query = null): ResourceArray
    {
        return $this->get('organizations', $query);
    }

    /**
     * @param \JsonSerializable $serializable
     *
     * @return object
     */
    public function prepareObjectForApi(\JsonSerializable $serializable)
    {
        $data = (object) $serializable->jsonSerialize();
        if (isset($data->sys)) {
            unset($data->sys);
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSdkName(): string
    {
        return 'contentful-management.php/';
    }

    /**
     * {@inheritdoc}
     */
    protected function getSdkVersion(): string
    {
        return self::VERSION;
    }

    /**
     * {@inheritdoc}
     */
    protected function getApiContentType(): string
    {
        return 'application/vnd.contentful.management.v1+json';
    }

    /**
     * {@inheritdoc}
     */
    protected function getExceptionMap(): array
    {
        return array_merge(parent::getExceptionMap(), [
            'BadRequest' => Exception\BadRequestException::class,
            'DefaultLocaleNotDeletable' => Exception\DefaultLocaleNotDeletableException::class,
            'FallbackLocaleNotDeletable' => Exception\FallbackLocaleNotDeletableException::class,
            'FallbackLocaleNotRenameable' => Exception\FallbackLocaleNotRenameableException::class,
            'InternalServerError' => Exception\InternalServerErrorException::class,
            'MissingKey' => Exception\MissingKeyException::class,
            // Overrides the generic exception
            'RateLimitExceeded' => Exception\RateLimitExceededException::class,
            'UnknownKey' => Exception\UnknownKeyException::class,
            'UnsupportedMediaType' => Exception\UnsupportedMediaTypeException::class,
            'ValidationFailed' => Exception\ValidationFailedException::class,
            'VersionMismatch' => Exception\VersionMismatchException::class,
        ]);
    }
}
