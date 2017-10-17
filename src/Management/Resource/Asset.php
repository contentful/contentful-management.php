<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Resource;

use Contentful\File\FileInterface;
use Contentful\File\UnprocessedFileInterface;
use Contentful\Management\Resource\Behavior\Archivable;
use Contentful\Management\Resource\Behavior\Creatable;
use Contentful\Management\Resource\Behavior\Deletable;
use Contentful\Management\Resource\Behavior\Publishable;
use Contentful\Management\Resource\Behavior\Updatable;

/**
 * Asset class.
 *
 * This class represents a resource with type "Asset" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/assets
 */
class Asset extends BaseResource implements Creatable, Updatable, Deletable, Publishable, Archivable
{
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

        if ($this->file !== null) {
            $asset['fields']->file = $this->file;
        }

        if ($this->title !== null) {
            $asset['fields']->title = $this->title;
        }

        if ($this->description !== null) {
            $asset['fields']->description = $this->description;
        }

        return $asset;
    }

    /**
     * @param string $locale
     *
     * @return string|null
     */
    public function getTitle(string $locale)
    {
        if ($this->title === null || !isset($this->title[$locale])) {
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
        if ($this->description === null || !isset($this->description[$locale])) {
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
        if ($this->file === null || !isset($this->file[$locale])) {
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
