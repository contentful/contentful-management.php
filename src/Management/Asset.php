<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

use Contentful\File\FileInterface;
use Contentful\File\UploadFile;

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

    public function __construct()
    {
        $this->sys = SystemProperties::withType('Asset');
    }

    /**
     * @return SystemProperties
     */
    public function getSystemProperties(): SystemProperties
    {
        return $this->sys;
    }

    public function getResourceUrlPart(): string
    {
        return 'assets';
    }

    /**
     * @param  string $locale
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
     * @param  string|null $title
     * @param  string      $locale
     *
     * @return $this
     */
    public function setTitle(string $title = null, string $locale)
    {
        $this->title[$locale] = $title;

        return $this;
    }

    /**
     * @param  string $locale
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
     * @param string|null $description
     * @param string       $locale
     *
     * @return $this
     */
    public function setDescription(string $description = null, string $locale)
    {
        $this->description[$locale] = $description;

        return $this;
    }

    /**
     * @param  string $locale
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
     * @param  UploadFile|null $file
     * @param  string          $locale
     *
     * @return $this
     */
    public function setFile(UploadFile $file = null, string $locale)
    {
        $this->file[$locale] = $file;

        return $this;
    }

    /**
     * Returns an object to be used by `json_encode` to serialize objects of this class.
     *
     * @return object
     *
     * @see http://php.net/manual/en/jsonserializable.jsonserialize.php JsonSerializable::jsonSerialize
     */
    public function jsonSerialize()
    {
        $fields = (object) [];
        if ($this->file !== null) {
            $fields->file = $this->file;
        }

        if ($this->title !== null) {
            $fields->title = $this->title;
        }

        if ($this->description !== null) {
            $fields->description = $this->description;
        }

        return (object) [
            'fields' => $fields,
            'sys' => $this->sys
        ];
    }
}
