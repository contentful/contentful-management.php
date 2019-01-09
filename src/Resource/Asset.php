<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Core\File\FileInterface;
use Contentful\Core\File\UnprocessedFileInterface;
use Contentful\Core\Resource\AssetInterface;
use Contentful\Management\Resource\Behavior\ArchivableTrait;
use Contentful\Management\Resource\Behavior\CreatableInterface;
use Contentful\Management\Resource\Behavior\DeletableTrait;
use Contentful\Management\Resource\Behavior\PublishableTrait;
use Contentful\Management\Resource\Behavior\UpdatableTrait;
use Contentful\Management\SystemProperties\Asset as SystemProperties;

/**
 * Asset class.
 *
 * This class represents a resource with type "Asset" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/assets
 */
class Asset extends BaseResource implements AssetInterface, CreatableInterface
{
    use ArchivableTrait,
        DeletableTrait,
        PublishableTrait,
        UpdatableTrait;

    /**
     * @var SystemProperties
     */
    protected $sys;

    /**
     * @var string[]
     */
    protected $title = [];

    /**
     * @var string[]
     */
    protected $description = [];

    /**
     * @var FileInterface[]
     */
    protected $file = [];

    /**
     * {@inheritdoc}
     */
    public function getSystemProperties(): SystemProperties
    {
        return $this->sys;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return [
            'sys' => $this->sys,
            'fields' => [
                'title' => (object) $this->title,
                'description' => (object) $this->description,
                'file' => (object) $this->file,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function asUriParameters(): array
    {
        return [
            'space' => $this->sys->getSpace()->getId(),
            'environment' => $this->sys->getEnvironment()->getId(),
            'asset' => $this->sys->getId(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getHeadersForCreation(): array
    {
        return [];
    }

    /**
     * Call the endpoint for processing the file associated to the asset.
     *
     * @param string|null $locale
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/assets/asset-processing
     */
    public function process(string $locale = \null)
    {
        $locales = $locale
            ? [$locale]
            : \array_keys($this->file);

        foreach ($locales as $locale) {
            $this->client->requestWithResource($this, 'PUT', '/files/'.$locale.'/process', [
                'headers' => ['X-Contentful-Version' => $this->sys->getVersion()],
            ]);
        }

        $this->client->fetchResource(
            \get_class($this),
            $this->asUriParameters(),
            \null,
            $this
        );
    }

    /**
     * @param string $locale
     *
     * @return string|null
     */
    public function getTitle(string $locale)
    {
        return $this->title[$locale] ?? \null;
    }

    /**
     * @param string      $locale
     * @param string|null $title
     *
     * @return static
     */
    public function setTitle(string $locale, string $title = \null)
    {
        if (!$title) {
            unset($this->title[$locale]);

            return $this;
        }

        $this->title[$locale] = $title;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getTitles(): array
    {
        return $this->title;
    }

    /**
     * @param string $locale
     *
     * @return string|null
     */
    public function getDescription(string $locale)
    {
        return $this->description[$locale] ?? \null;
    }

    /**
     * @param string      $locale
     * @param string|null $description
     *
     * @return static
     */
    public function setDescription(string $locale, string $description = \null)
    {
        if (!$description) {
            unset($this->description[$locale]);

            return $this;
        }

        $this->description[$locale] = $description;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getDescriptions(): array
    {
        return $this->description;
    }

    /**
     * @param string $locale
     *
     * @return FileInterface|null
     */
    public function getFile(string $locale)
    {
        return $this->file[$locale] ?? \null;
    }

    /**
     * @param string                        $locale
     * @param UnprocessedFileInterface|null $file
     *
     * @return static
     */
    public function setFile(string $locale, UnprocessedFileInterface $file = \null)
    {
        if (!$file) {
            unset($this->file[$locale]);

            return $this;
        }

        $this->file[$locale] = $file;

        return $this;
    }

    /**
     * @return FileInterface[]
     */
    public function getFiles(): array
    {
        return $this->file;
    }
}
