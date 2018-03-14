<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\Core\File\FileInterface;
use Contentful\Core\File\UnprocessedFileInterface;
use Contentful\Management\Resource\Behavior\ArchivableTrait;
use Contentful\Management\Resource\Behavior\CreatableInterface;
use Contentful\Management\Resource\Behavior\DeletableTrait;
use Contentful\Management\Resource\Behavior\PublishableTrait;
use Contentful\Management\Resource\Behavior\UpdatableTrait;

/**
 * Asset class.
 *
 * This class represents a resource with type "Asset" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/assets
 */
class Asset extends BaseResource implements CreatableInterface
{
    use ArchivableTrait,
        DeletableTrait,
        PublishableTrait,
        UpdatableTrait;

    /**
     * @var string[]
     */
    protected $title;

    /**
     * @var string[]
     */
    protected $description;

    /**
     * @var FileInterface[]
     */
    protected $file;

    /**
     * Asset constructor.
     */
    public function __construct()
    {
        parent::__construct('Asset');
    }

    /**
     * Returns an array to be used by "json_encode" to serialize objects of this class.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $asset = [
            'sys' => $this->sys,
            'fields' => new \stdClass(),
        ];

        if (null !== $this->file) {
            $asset['fields']->file = $this->file;
        }

        if (null !== $this->title) {
            $asset['fields']->title = $this->title;
        }

        if (null !== $this->description) {
            $asset['fields']->description = $this->description;
        }

        return $asset;
    }

    /**
     * {@inheritdoc}
     */
    public function asUriParameters(): array
    {
        return [
            'space' => $this->sys->getSpace()->getId(),
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
    public function process(string $locale = null)
    {
        $locales = $locale
            ? [$locale]
            : \array_keys($this->file);

        foreach ($locales as $locale) {
            $this->client->requestWithResource($this, 'PUT', '/files/'.$locale.'/process', [
                'headers' => ['X-Contentful-Version' => $this->sys->getVersion()],
            ], false);
        }

        return $this->client->fetchResource(static::class, $this->asUriParameters(), null, $this);
    }

    /**
     * @param string $locale
     *
     * @return string|null
     */
    public function getTitle(string $locale)
    {
        if (null === $this->title || !isset($this->title[$locale])) {
            return null;
        }

        return $this->title[$locale];
    }

    /**
     * @param string      $locale
     * @param string|null $title
     *
     * @return static
     */
    public function setTitle(string $locale, string $title = null)
    {
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
        if (null === $this->description || !isset($this->description[$locale])) {
            return null;
        }

        return $this->description[$locale];
    }

    /**
     * @param string      $locale
     * @param string|null $description
     *
     * @return static
     */
    public function setDescription(string $locale, string $description = null)
    {
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
        if (null === $this->file || !isset($this->file[$locale])) {
            return null;
        }

        return $this->file[$locale];
    }

    /**
     * @param string                        $locale
     * @param UnprocessedFileInterface|null $file
     *
     * @return static
     */
    public function setFile(string $locale, UnprocessedFileInterface $file = null)
    {
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
