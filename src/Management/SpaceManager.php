<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

use Contentful\ResourceArray;

class SpaceManager
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var ResourceBuilder
     */
    private $builder;

    /**
     * @var string
     */
    private $spaceId;

    /**
     * SpaceManager constructor.
     *
     * @param  Client          $client
     * @param  ResourceBuilder $builder
     * @param  string          $spaceId
     */
    public function __construct(Client $client, ResourceBuilder $builder, $spaceId)
    {
        $this->client = $client;
        $this->builder = $builder;
        $this->spaceId = $spaceId;
    }

    public function getLocale(string $localeId): Locale
    {
        $response = $this->client->request('GET', 'spaces/' . $this->spaceId . '/locales/' . $localeId);

        return $this->builder->buildObjectsFromRawData($response);
    }

    public function getLocales(): ResourceArray
    {
        $response = $this->client->request('GET', 'spaces/' . $this->spaceId . '/locales');

        return $this->builder->buildObjectsFromRawData($response);
    }

    public function createLocale(Locale $locale, string $id = null)
    {
        $body = $this->client->encodeJson($this->client->prepareObjectForApi($locale));

        $path = 'spaces/' . $this->spaceId . '/locales';
        if ($id !== null) {
            $path .= '/' . $id;
        }

        $response = $this->client->request('POST', $path, ['body' => $body]);
        $this->builder->updateObjectFromRawData($locale, $response);
    }

    public function updateLocale(Locale $locale)
    {
        $sys = $locale->getSystemProperties();
        $body = $this->client->encodeJson($this->client->prepareObjectForApi($locale));
        $additionalHeaders = ['X-Contentful-Version' => $sys->getVersion()];

        $response = $this->client->request('PUT', 'spaces/' . $this->spaceId . '/locales/' . $sys->getId(), [
            'additionalHeaders' => $additionalHeaders,
            'body' => $body
        ]);

        $this->builder->updateObjectFromRawData($locale, $response);
    }

    public function deleteLocale(Locale $locale)
    {
        $sys = $locale->getSystemProperties();
        $this->client->request('DELETE', 'spaces/' . $this->spaceId . '/locales/' . $sys->getId());
    }

    public function getContentType($contentTypeId): ContentType
    {
        $response = $this->client->request('GET', 'spaces/' . $this->spaceId . '/content_types/' . $contentTypeId);

        return $this->builder->buildObjectsFromRawData($response);
    }

    public function getContentTypes(): ResourceArray
    {
        $response = $this->client->request('GET', 'spaces/' . $this->spaceId . '/content_types');

        return $this->builder->buildObjectsFromRawData($response);
    }

    public function createContentType(ContentType $contentType, string $id = null)
    {
        $body = $this->client->encodeJson($this->client->prepareObjectForApi($contentType));

        $path = 'spaces/' . $this->spaceId . '/content_types';
        if ($id !== null) {
            $path .= '/' . $id;
        }

        $response = $this->client->request('POST', $path, ['body' => $body]);
        $this->builder->updateObjectFromRawData($contentType, $response);
    }

    public function updateContentType(ContentType $contentType)
    {
        $sys = $contentType->getSystemProperties();
        $body = $this->client->encodeJson($this->client->prepareObjectForApi($contentType));
        $additionalHeaders = ['X-Contentful-Version' => $sys->getVersion()];

        $response = $this->client->request('PUT', 'spaces/' . $this->spaceId . '/content_types/' . $sys->getId(), [
            'additionalHeaders' => $additionalHeaders,
            'body' => $body
        ]);

        $this->builder->updateObjectFromRawData($contentType, $response);
    }

    public function activateContentType(ContentType $contentType)
    {
        $sys = $contentType->getSystemProperties();
        $additionalHeaders = ['X-Contentful-Version' => $sys->getVersion()];

        $response = $this->client->request('PUT', 'spaces/' . $this->spaceId . '/content_types/' . $sys->getId() . '/published', [
            'additionalHeaders' => $additionalHeaders
        ]);

        $this->builder->updateObjectFromRawData($contentType, $response);
    }

    public function deactivateContentType(ContentType $contentType)
    {
        $sys = $contentType->getSystemProperties();
        $additionalHeaders = ['X-Contentful-Version' => $sys->getVersion()];

        $response = $this->client->request('DELETE', 'spaces/' . $this->spaceId . '/content_types/' . $sys->getId() . '/published', [
            'additionalHeaders' => $additionalHeaders
        ]);

        $this->builder->updateObjectFromRawData($contentType, $response);
    }

    public function deleteContentType(ContentType $contentType)
    {
        $sys = $contentType->getSystemProperties();
        $this->client->request('DELETE', 'spaces/' . $this->spaceId . '/content_types/' . $sys->getId());
    }
}
