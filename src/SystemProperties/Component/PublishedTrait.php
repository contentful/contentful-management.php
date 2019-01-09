<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\SystemProperties\Component;

use Contentful\Core\Api\DateTimeImmutable;
use Contentful\Core\Api\Link;

trait PublishedTrait
{
    use EditedTrait;

    /**
     * @var DateTimeImmutable|null
     */
    protected $firstPublishedAt;

    /**
     * @var int|null
     */
    protected $publishedCounter;

    /**
     * @var DateTimeImmutable|null
     */
    protected $publishedAt;

    /**
     * @var Link|null
     */
    protected $publishedBy;

    /**
     * @var int|null
     */
    private $publishedVersion;

    /**
     * @param array $data
     */
    protected function initPublished(array $data)
    {
        $this->initEdited($data);

        if (!isset($data['publishedAt'])) {
            return;
        }

        $this->firstPublishedAt = new DateTimeImmutable($data['firstPublishedAt']);
        $this->publishedCounter = $data['publishedCounter'];
        $this->publishedAt = new DateTimeImmutable($data['publishedAt']);
        $this->publishedBy = new Link($data['publishedBy']['sys']['id'], $data['publishedBy']['sys']['linkType']);
        $this->publishedVersion = $data['publishedVersion'];
    }

    /**
     * @return array
     */
    protected function jsonSerializePublished(): array
    {
        return \array_filter(\array_merge($this->jsonSerializeEdited(), [
            'firstPublishedAt' => \null !== $this->firstPublishedAt
                ? $this->firstPublishedAt->jsonSerialize()
                : \null,
            'publishedCounter' => \null !== $this->publishedCounter
                ? $this->publishedCounter
                : \null,
            'publishedAt' => \null !== $this->publishedAt
                ? $this->publishedAt->jsonSerialize()
                : \null,
            'publishedBy' => \null !== $this->publishedBy
                ? $this->publishedBy->jsonSerialize()
                : \null,
            'publishedVersion' => \null !== $this->publishedVersion
                ? $this->publishedVersion
                : \null,
        ]));
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getFirstPublishedAt()
    {
        return $this->firstPublishedAt;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * @return Link|null
     */
    public function getPublishedBy()
    {
        return $this->publishedBy;
    }

    /**
     * @return int|null
     */
    public function getPublishedCounter()
    {
        return $this->publishedCounter;
    }

    /**
     * @return int|null
     */
    public function getPublishedVersion()
    {
        return $this->publishedVersion;
    }

    /**
     * @return bool
     */
    public function isDraft(): bool
    {
        return \null === $this->publishedVersion;
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return \null !== $this->publishedVersion;
    }

    /**
     * @return bool
     */
    public function isUpdated(): bool
    {
        // The act of publishing an entity increases its version by 1, so any entry which has
        // 2 versions higher or more than the publishedVersion has unpublished changes.
        return \null !== $this->publishedVersion && $this->version > $this->publishedVersion + 1;
    }
}
