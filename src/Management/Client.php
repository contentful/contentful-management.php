<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

use Contentful\Client as BaseClient;
use Contentful\ResourceArray;

class Client extends BaseClient
{
    const VERSION = '0.6.0-dev';

    /**
     * @var ResourceBuilder
     */
    private $builder;

    /**
     * Client constructor.
     *
     * @param string $token
     * @param array $options
     *
     * @api
     */
    public function __construct(string $token, array $options = [])
    {
        $baseUri = 'https://api.contentful.com/';
        $api = 'MANAGEMENT';

        $options = array_replace([
            'guzzle' => null,
            'logger' => null,
            'uriOverride' => null
        ], $options);

        $guzzle = $options['guzzle'];
        $logger = $options['logger'];
        $uriOverride = $options['uriOverride'];

        if ($uriOverride !== null) {
            $baseUri = $uriOverride;
        }

        parent::__construct($token, $baseUri, $api, $logger, $guzzle);

        $this->builder = new ResourceBuilder;
    }

    public function request($method, $path, array $options = [])
    {
        return parent::request($method, $path, $options);
    }

    public function encodeJson($object)
    {
        return parent::encodeJson($object);
    }

    /**
     * @param  string $spaceId
     *
     * @return Space
     */
    public function getSpace($spaceId): Space
    {
        $response = $this->request('GET', 'spaces/' . $spaceId);

        return $this->builder->buildObjectsFromRawData($response);
    }

    /**
     * @return ResourceArray
     */
    public function getSpaces(): ResourceArray
    {
        $response = $this->request('GET', 'spaces');

        return $this->builder->buildObjectsFromRawData($response);
    }

    /**
     * @param  Space       $space
     * @param  string|null $organizationId
     * @param  string      $defaultLocale
     */
    public function createSpace(Space $space, string $organizationId = null, string $defaultLocale = 'en-US')
    {
        $additionalHeaders = $organizationId ? ['X-Contentful-Organization' => $organizationId] : [];
        $bodyData = $this->prepareObjectForApi($space);
        if ($defaultLocale !== 'en-US') {
            $bodyData->defaultLocale = $defaultLocale;
        }
        $body = $this->encodeJson($bodyData);

        $response = $this->request('POST', 'spaces', [
            'additionalHeaders' => $additionalHeaders,
            'body' => $body
        ]);
        $this->builder->updateObjectFromRawData($space, $response);
    }

    /**
     * @param  Space $space
     */
    public function updateSpace(Space $space)
    {
        $sys = $space->getSystemProperties();
        $body = $this->encodeJson($this->prepareObjectForApi($space));
        $additionalHeaders = ['X-Contentful-Version' => $sys->getVersion()];
        $response = $this->request('PUT', 'spaces/' . $sys->getId(), [
            'additionalHeaders' => $additionalHeaders,
            'body' => $body
        ]);
        $this->builder->updateObjectFromRawData($space, $response);
    }

    /**
     * @param  Space $space
     */
    public function deleteSpace(Space $space)
    {
        $sys = $space->getSystemProperties();
        $this->request('DELETE', 'spaces/' . $sys->getId());
    }

    public function prepareObjectForApi(\JsonSerializable $serializable)
    {
        $data = $serializable->jsonSerialize();
        if (isset($data->sys)) {
            unset($data->sys);
        }

        return $data;
    }

    /**
     * The name of the library to be used in the User-Agent header.
     *
     * @return string
     */
    protected function getSdkNameAndVersion()
    {
        return 'contentful-management.php/' . self::VERSION;
    }
}
