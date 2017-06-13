<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

use Contentful\Exception\SpaceMismatchException;
use Contentful\JsonHelper;
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

    public function checkSpaceMismatch(ResourceInterface $resource)
    {
        $sys = $resource->getSystemProperties();
        if ($resource instanceof Space) {
            $resourceSpaceId = $sys->getId();
        } else {
            $resourceSpaceId = $sys->getSpace()->getId();
        }

        if ($resourceSpaceId !== $this->spaceId) {
            throw new SpaceMismatchException('Can\'t perform action on space ' . $resource->getSystemProperties()->getSpace()->getId() . ' with a SpaceManager responsible for ' . $this->spaceId . '.');
        }
    }

    public function publish(Publishable $resource)
    {
        $this->checkSpaceMismatch($resource);

        $sys = $resource->getSystemProperties();
        $urlParts = [
            'spaces',
            $this->spaceId,
            $resource->getResourceUrlPart(),
            $sys->getId(),
            'published'
        ];

        $response = $this->client->request('PUT', implode('/', $urlParts), [
            'additionalHeaders' => ['X-Contentful-Version' => $sys->getVersion()]
        ]);

        $this->builder->updateObjectFromRawData($resource, $response);
    }

    public function unpublish(Publishable $resource)
    {
        $this->checkSpaceMismatch($resource);

        $sys = $resource->getSystemProperties();
        $urlParts = [
            'spaces',
            $this->spaceId,
            $resource->getResourceUrlPart(),
            $sys->getId(),
            'published'
        ];

        $response = $this->client->request('DELETE', implode('/', $urlParts), [
            'additionalHeaders' => ['X-Contentful-Version' => $sys->getVersion()]
        ]);

        $this->builder->updateObjectFromRawData($resource, $response);
    }

    public function archive(Archivable $resource)
    {
        $this->checkSpaceMismatch($resource);

        $sys = $resource->getSystemProperties();
        $urlParts = [
            'spaces',
            $this->spaceId,
            $resource->getResourceUrlPart(),
            $sys->getId(),
            'archived'
        ];

        $response = $this->client->request('PUT', implode('/', $urlParts), [
            'additionalHeaders' => ['X-Contentful-Version' => $sys->getVersion()]
        ]);

        $this->builder->updateObjectFromRawData($resource, $response);
    }

    public function unarchive(Archivable $resource)
    {
        $this->checkSpaceMismatch($resource);

        $sys = $resource->getSystemProperties();
        $urlParts = [
            'spaces',
            $this->spaceId,
            $resource->getResourceUrlPart(),
            $sys->getId(),
            'archived'
        ];

        $response = $this->client->request('DELETE', implode('/', $urlParts), [
            'additionalHeaders' => ['X-Contentful-Version' => $sys->getVersion()]
        ]);

        $this->builder->updateObjectFromRawData($resource, $response);
    }

    public function delete(Deletable $resource)
    {
        $sys = $resource->getSystemProperties();
        $urlParts = [
            'spaces',
            $this->spaceId,
            $resource->getResourceUrlPart(),
            $sys->getId()
        ];

        $this->client->request('DELETE', implode('/', $urlParts));
    }

    public function update(Updatable $resource)
    {
        $sys = $resource->getSystemProperties();
        $body = JsonHelper::encode($this->client->prepareObjectForApi($resource));
        $urlParts = [
            'spaces',
            $this->spaceId,
            $resource->getResourceUrlPart(),
            $sys->getId()
        ];

        $response = $this->client->request('PUT', implode('/', $urlParts), [
            'additionalHeaders' => ['X-Contentful-Version' => $sys->getVersion()],
            'body' => $body
        ]);

        $this->builder->updateObjectFromRawData($resource, $response);
    }

    public function create(Creatable $resource, string $id = null)
    {
        $body = JsonHelper::encode($this->client->prepareObjectForApi($resource));
        $additionalHeaders = [];
        $urlParts = [
            'spaces',
            $this->spaceId,
            $resource->getResourceUrlPart(),
        ];

        if ($id !== null) {
            $urlParts[] = $id;
        }

        if ($resource instanceof Entry) {
            $additionalHeaders = ['X-Contentful-Content-Type' => $resource->getSystemProperties()->getContentType()->getId()];
        }

        $response = $this->client->request('POST', implode('/', $urlParts), [
            'additionalHeaders' => $additionalHeaders,
            'body' => $body
        ]);

        $this->builder->updateObjectFromRawData($resource, $response);
    }

    public function getAsset($assetId): Asset
    {
        $response = $this->client->request('GET', 'spaces/' . $this->spaceId . '/assets/' . $assetId);

        return $this->builder->buildObjectsFromRawData($response);
    }

    public function getAssets(Query $query = null): ResourceArray
    {
        return $this->getAndBuildCollection('spaces/' . $this->spaceId . '/assets', $query);
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

    public function getLocale(string $localeId): Locale
    {
        $response = $this->client->request('GET', 'spaces/' . $this->spaceId . '/locales/' . $localeId);

        return $this->builder->buildObjectsFromRawData($response);
    }

    public function getLocales(): ResourceArray
    {
        return $this->getAndBuildCollection('spaces/' . $this->spaceId . '/locales');
    }

    public function getContentType(string $contentTypeId): ContentType
    {
        $response = $this->client->request('GET', 'spaces/' . $this->spaceId . '/content_types/' . $contentTypeId);

        return $this->builder->buildObjectsFromRawData($response);
    }

    public function getContentTypes(Query $query = null): ResourceArray
    {
        return $this->getAndBuildCollection('spaces/' . $this->spaceId . '/content_types', $query);
    }

    public function getEntry(string $entryId): Entry
    {
        $response = $this->client->request('GET', 'spaces/' . $this->spaceId . '/entries/' . $entryId);

        return $this->builder->buildObjectsFromRawData($response);
    }

    public function getEntries(Query $query = null): ResourceArray
    {
        return $this->getAndBuildCollection('spaces/' . $this->spaceId . '/entries', $query);
    }

    public function getAndBuildCollection(string $path, Query $query = null): ResourceArray
    {
        $queryData = $query !== null ? $query->getQueryData() : [];

        $response = $this->client->request('GET', $path, [
            'query' => $queryData
        ]);

        return $this->builder->buildObjectsFromRawData($response);
    }
}
