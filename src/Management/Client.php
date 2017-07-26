<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

use function GuzzleHttp\json_encode;
use Contentful\Client as BaseClient;
use Contentful\Management\Resource\Space;
use Contentful\Management\Resource\User;
use Contentful\ResourceArray;

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
    const VERSION = '0.6.0-dev';

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
     * Client constructor.
     *
     * @param string $token
     * @param array  $options
     */
    public function __construct(string $token, array $options = [])
    {
        $baseUri = self::URI_MANAGEMENT;
        $api = 'MANAGEMENT';

        $options = array_replace([
            'guzzle' => null,
            'logger' => null,
            'uriOverride' => null,
        ], $options);

        $guzzle = $options['guzzle'];
        $logger = $options['logger'];
        $uriOverride = $options['uriOverride'];

        if ($uriOverride !== null) {
            $baseUri = $uriOverride;
        }

        parent::__construct($token, $baseUri, $api, $logger, $guzzle);

        $this->builder = new ResourceBuilder();
    }

    /**
     * @param string $spaceId
     *
     * @return SpaceManager
     */
    public function getSpaceManager(string $spaceId): SpaceManager
    {
        return new SpaceManager($this, $this->builder, $spaceId);
    }

    public function request($method, $path, array $options = [])
    {
        return parent::request($method, $path, $options);
    }

    /**
     * @param string $spaceId
     *
     * @return Space
     */
    public function getSpace($spaceId): Space
    {
        $response = $this->request('GET', 'spaces/'.$spaceId);

        return $this->builder->buildObjectsFromRawData($response);
    }

    /**
     * @param Query|null $query
     *
     * @return ResourceArray
     */
    public function getSpaces(Query $query = null): ResourceArray
    {
        $query = $query !== null ? $query : new Query();
        $queryData = $query->getQueryData();

        $response = $this->request('GET', 'spaces', [
            'query' => $queryData,
        ]);

        return $this->builder->buildObjectsFromRawData($response);
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
            $bodyData['defaultLocale'] = $defaultLocale;
        }
        $body = json_encode($bodyData, JSON_UNESCAPED_UNICODE);

        $response = $this->request('POST', 'spaces', [
            'additionalHeaders' => $additionalHeaders,
            'body' => $body,
        ]);
        $this->builder->updateObjectFromRawData($space, $response);
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
        $this->builder->updateObjectFromRawData($space, $response);
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
        $response = $this->request('GET', 'users/me');

        return $this->builder->buildObjectsFromRawData($response);
    }

    /**
     * @param Query|null $query
     *
     * @return ResourceArray
     */
    public function getOrganizations(Query $query = null): ResourceArray
    {
        $query = $query !== null ? $query : new Query();
        $queryData = $query->getQueryData();

        $response = $this->request('GET', 'organizations', [
            'query' => $queryData,
        ]);

        return $this->builder->buildObjectsFromRawData($response);
    }

    /**
     * @param \JsonSerializable $serializable
     *
     * @return array|object
     */
    public function prepareObjectForApi(\JsonSerializable $serializable)
    {
        $data = $serializable->jsonSerialize();
        if (is_array(($data)) && isset($data['sys'])) {
            unset($data['sys']);
        }
        if (is_object(($data)) && isset($data->sys)) {
            unset($data->sys);
        }

        return $data;
    }

    /**
     * The name of the library to be used in the User-Agent header.
     *
     * @return string
     */
    protected function getSdkName()
    {
        return 'contentful-management.php/';
    }

    /**
     * The version of the library to be used in the User-Agent header.
     *
     * @return string
     */
    protected function getSdkVersion()
    {
        return self::VERSION;
    }

    /**
     * Returns the Content-Type (MIME-Type) to be used when communication with the API.
     *
     * @return string
     */
    protected function getApiContentType()
    {
        return 'application/vnd.contentful.management.v1+json';
    }

    /**
     * @return string[]
     */
    protected function getExceptionMap()
    {
        return array_merge(parent::getExceptionMap(), [
            'BadRequest' => Exception\BadRequestException::class,
            'DefaultLocaleNotDeletable' => Exception\DefaultLocaleNotDeletableException::class,
            'FallbackLocaleNotDeletable' => Exception\FallbackLocaleNotDeletableException::class,
            'FallbackLocaleNotRenameable' => Exception\FallbackLocaleNotRenameableException::class,
            'InternalServerError' => Exception\InternalServerErrorException::class,
            'MissingKey' => Exception\MissingKeyException::class,
            'RateLimitExceeded' => Exception\RateLimitExceededException::class, // Overrides the generic exception
            'UnknownKey' => Exception\UnknownKeyException::class,
            'UnsupportedMediaType' => Exception\UnsupportedMediaTypeException::class,
            'ValidationFailed' => Exception\ValidationFailedException::class,
            'VersionMismatch' => Exception\VersionMismatchException::class,
        ]);
    }
}
