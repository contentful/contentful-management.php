<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management\Resource;

use Contentful\File\FileInterface;
use Contentful\File\UnprocessedFileInterface;
use Contentful\Management\Behavior\Archivable;
use Contentful\Management\Behavior\Creatable;
use Contentful\Management\Behavior\Deletable;
use Contentful\Management\Behavior\Publishable;
use Contentful\Management\Behavior\Updatable;
use Contentful\Management\SystemProperties;

/**
 * Asset class.
 *
 * This class represents a resource with type "Asset" in Contentful.
 *
 * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/assets
 */
class Asset implements SpaceScopedResourceInterface, Publishable, Archivable, Deletable, Updatable, Creatable
{
    /**
     * @var SystemProperties
     */
    private $sys;

    /**
     * @var string[]
     */
    private $title;

    /**
     * @var string[]
     */
    private $description;

    /**
     * @var FileInterface[]
     */
    private $file;

    /**
     * Asset constructor.
     */
    public function __construct()
    {
        $this->sys = SystemProperties::withType('Asset');
    }

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
    public function getResourceUriPart(): string
    {
        return 'assets';
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
     * @return $this
     */
    public function setTitle(string $locale, string $title = null)
    {
        $this->title[$locale] = $title;

        return $this;
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
     * @return $this
     */
    public function setDescription(string $locale, string $description = null)
    {
        $this->description[$locale] = $description;

        return $this;
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
     * @return $this
     */
    public function setFile(string $locale, UnprocessedFileInterface $file = null)
    {
        $this->file[$locale] = $file;

        return $this;
    }

    /**
     * Returns an array to be used by `json_encode` to serialize objects of this class.
     *
     * @return array
     *
     * @see http://php.net/manual/en/jsonserializable.jsonserialize.php JsonSerializable::jsonSerialize
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
}
