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

    public function getAsset($assetId): Asset
    {
        $response = $this->client->request('GET', 'spaces/' . $this->spaceId . '/assets/' . $assetId);

        return $this->builder->buildObjectsFromRawData($response);
    }

    public function getAssets(): ResourceArray
    {
        $response = $this->client->request('GET', 'spaces/' . $this->spaceId . '/assets');

        return $this->builder->buildObjectsFromRawData($response);
    }

    public function createAsset(Asset $asset, string $id = null)
    {
        $body = $this->client->encodeJson($this->client->prepareObjectForApi($asset));

        $path = 'spaces/' . $this->spaceId . '/assets';
        if ($id !== null) {
            $path .= '/' . $id;
        }

        $response = $this->client->request('POST', $path, ['body' => $body]);
        $this->builder->updateObjectFromRawData($asset, $response);
    }

    public function updateAsset(Asset $asset)
    {
        $sys = $asset->getSystemProperties();
        $body = $this->client->encodeJson($this->client->prepareObjectForApi($asset));
        $additionalHeaders = ['X-Contentful-Version' => $sys->getVersion()];

        $response = $this->client->request('PUT', 'spaces/' . $this->spaceId . '/assets/' . $sys->getId(), [
            'additionalHeaders' => $additionalHeaders,
            'body' => $body
        ]);

        $this->builder->updateObjectFromRawData($asset, $response);
    }

    public function processAsset(Asset $asset, string $locale)
    {
        $sys = $asset->getSystemProperties();
        $additionalHeaders = ['X-Contentful-Version' => $sys->getVersion()];

        $this->client->request('PUT', 'spaces/' . $this->spaceId . '/assets/' . $sys->getId() . '/files/' . $locale . '/process', [
            'additionalHeaders' => $additionalHeaders
        ]);

        // Fetch the Asset because it's not returned from the above API call
        $response = $this->client->request('GET', 'spaces/' . $this->spaceId . '/assets/' . $sys->getId());

        $this->builder->updateObjectFromRawData($asset, $response);
    }

    public function publishAsset(Asset $asset)
    {
        $sys = $asset->getSystemProperties();
        $additionalHeaders = ['X-Contentful-Version' => $sys->getVersion()];

        $response = $this->client->request('PUT', 'spaces/' . $this->spaceId . '/assets/' . $sys->getId() . '/published', [
            'additionalHeaders' => $additionalHeaders
        ]);

        $this->builder->updateObjectFromRawData($asset, $response);
    }

    public function unpublishAsset(Asset $asset)
    {
        $sys = $asset->getSystemProperties();
        $additionalHeaders = ['X-Contentful-Version' => $sys->getVersion()];

        $response = $this->client->request('DELETE', 'spaces/' . $this->spaceId . '/assets/' . $sys->getId() . '/published', [
            'additionalHeaders' => $additionalHeaders
        ]);

        $this->builder->updateObjectFromRawData($asset, $response);
    }

    public function archiveAsset(Asset $asset)
    {
        $sys = $asset->getSystemProperties();
        $additionalHeaders = ['X-Contentful-Version' => $sys->getVersion()];

        $response = $this->client->request('PUT', 'spaces/' . $this->spaceId . '/assets/' . $sys->getId() . '/archived', [
            'additionalHeaders' => $additionalHeaders
        ]);

        $this->builder->updateObjectFromRawData($asset, $response);
    }

    public function unarchiveAsset(Asset $asset)
    {
        $sys = $asset->getSystemProperties();
        $additionalHeaders = ['X-Contentful-Version' => $sys->getVersion()];

        $response = $this->client->request('DELETE', 'spaces/' . $this->spaceId . '/assets/' . $sys->getId() . '/archived', [
            'additionalHeaders' => $additionalHeaders
        ]);

        $this->builder->updateObjectFromRawData($asset, $response);
    }

    public function deleteAsset(Asset $asset)
    {
        $sys = $asset->getSystemProperties();
        $this->client->request('DELETE', 'spaces/' . $this->spaceId . '/assets/' . $sys->getId());
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

    public function getEntry($entryId): Entry
    {
        $response = $this->client->request('GET', 'spaces/' . $this->spaceId . '/entries/' . $entryId);

        return $this->builder->buildObjectsFromRawData($response);
    }

    public function getEntries(): ResourceArray
    {
        $response = $this->client->request('GET', 'spaces/' . $this->spaceId . '/entries');

        return $this->builder->buildObjectsFromRawData($response);
    }

    public function createEntry(Entry $entry, string $contentTypeID, string $id = null)
    {
        $body = $this->client->encodeJson($this->client->prepareObjectForApi($entry));

        $path = 'spaces/' . $this->spaceId . '/entries';
        if ($id !== null) {
            $path .= '/' . $id;
        }

        $additionalHeaders = ['X-Contentful-Content-Type' => $contentTypeID];

        $response = $this->client->request('POST', $path, [
            'additionalHeaders' => $additionalHeaders,
            'body' => $body
        ]);
        $this->builder->updateObjectFromRawData($entry, $response);
    }

    public function updateEntry(Entry $entry)
    {
        $sys = $entry->getSystemProperties();
        $body = $this->client->encodeJson($this->client->prepareObjectForApi($entry));
        $additionalHeaders = ['X-Contentful-Version' => $sys->getVersion()];

        $response = $this->client->request('PUT', 'spaces/' . $this->spaceId . '/entries/' . $sys->getId(), [
            'additionalHeaders' => $additionalHeaders,
            'body' => $body
        ]);

        $this->builder->updateObjectFromRawData($entry, $response);
    }

    public function publishEntry(Entry $entry)
    {
        $sys = $entry->getSystemProperties();
        $additionalHeaders = ['X-Contentful-Version' => $sys->getVersion()];

        $response = $this->client->request('PUT', 'spaces/' . $this->spaceId . '/entries/' . $sys->getId() . '/published', [
            'additionalHeaders' => $additionalHeaders
        ]);

        $this->builder->updateObjectFromRawData($entry, $response);
    }

    public function unpublishEntry(Entry $entry)
    {
        $sys = $entry->getSystemProperties();
        $additionalHeaders = ['X-Contentful-Version' => $sys->getVersion()];

        $response = $this->client->request('DELETE', 'spaces/' . $this->spaceId . '/entries/' . $sys->getId() . '/published', [
            'additionalHeaders' => $additionalHeaders
        ]);

        $this->builder->updateObjectFromRawData($entry, $response);
    }

    public function archiveEntry(Entry $entry)
    {
        $sys = $entry->getSystemProperties();
        $additionalHeaders = ['X-Contentful-Version' => $sys->getVersion()];

        $response = $this->client->request('PUT', 'spaces/' . $this->spaceId . '/entries/' . $sys->getId() . '/archived', [
            'additionalHeaders' => $additionalHeaders
        ]);

        $this->builder->updateObjectFromRawData($entry, $response);
    }

    public function unarchiveEntry(Entry $entry)
    {
        $sys = $entry->getSystemProperties();
        $additionalHeaders = ['X-Contentful-Version' => $sys->getVersion()];

        $response = $this->client->request('DELETE', 'spaces/' . $this->spaceId . '/entries/' . $sys->getId() . '/archived', [
            'additionalHeaders' => $additionalHeaders
        ]);

        $this->builder->updateObjectFromRawData($entry, $response);
    }

    public function deleteEntry(Entry $entry)
    {
        $sys = $entry->getSystemProperties();
        $this->client->request('DELETE', 'spaces/' . $this->spaceId . '/entries/' . $sys->getId());
    }
}
