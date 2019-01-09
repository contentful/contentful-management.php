<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\ContentType\Validation;

/**
 * AssetImageDimensionsValidation class.
 *
 * Defines minimum and maximum dimensions of an image, in pixels.
 *
 * Applicable to:
 * - Link (to image assets)
 */
class AssetImageDimensionsValidation implements ValidationInterface
{
    /**
     * @var int|null
     */
    private $minWidth;

    /**
     * @var int|null
     */
    private $maxWidth;

    /**
     * @var int|null
     */
    private $minHeight;

    /**
     * @var int|null
     */
    private $maxHeight;

    /**
     * AssetImageDimensionsValidation constructor.
     *
     * @param int|null $minWidth
     * @param int|null $maxWidth
     * @param int|null $minHeight
     * @param int|null $maxHeight
     */
    public function __construct(int $minWidth = \null, int $maxWidth = \null, int $minHeight = \null, int $maxHeight = \null)
    {
        $this->minWidth = $minWidth;
        $this->maxWidth = $maxWidth;
        $this->minHeight = $minHeight;
        $this->maxHeight = $maxHeight;
    }

    /**
     * {@inheritdoc}
     */
    public static function getValidFieldTypes(): array
    {
        return ['Link'];
    }

    /**
     * @return int|null
     */
    public function getMinWidth()
    {
        return $this->minWidth;
    }

    /**
     * @param int|null $minWidth
     *
     * @return static
     */
    public function setMinWidth(int $minWidth = \null)
    {
        $this->minWidth = $minWidth;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxWidth()
    {
        return $this->maxWidth;
    }

    /**
     * @param int|null $maxWidth
     *
     * @return static
     */
    public function setMaxWidth(int $maxWidth = \null)
    {
        $this->maxWidth = $maxWidth;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinHeight()
    {
        return $this->minHeight;
    }

    /**
     * @param int|null $minHeight
     *
     * @return static
     */
    public function setMinHeight(int $minHeight = \null)
    {
        $this->minHeight = $minHeight;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxHeight()
    {
        return $this->maxHeight;
    }

    /**
     * @param int|null $maxHeight
     *
     * @return static
     */
    public function setMaxHeight(int $maxHeight = \null)
    {
        $this->maxHeight = $maxHeight;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        $data = [];
        if (\null !== $this->minWidth || \null !== $this->maxWidth) {
            $withData = [];
            if (\null !== $this->minWidth) {
                $withData['min'] = $this->minWidth;
            }
            if (\null !== $this->maxWidth) {
                $withData['max'] = $this->maxWidth;
            }

            $data['width'] = $withData;
        }
        if (\null !== $this->minHeight || \null !== $this->maxHeight) {
            $heightData = [];
            if (\null !== $this->minHeight) {
                $heightData['min'] = $this->minHeight;
            }
            if (\null !== $this->maxHeight) {
                $heightData['max'] = $this->maxHeight;
            }

            $data['height'] = $heightData;
        }

        return [
            'assetImageDimensions' => $data,
        ];
    }
}
