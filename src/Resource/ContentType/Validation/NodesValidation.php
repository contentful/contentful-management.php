<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\ContentType\Validation;

use Contentful\Management\Resource\ContentType\Validation\Nodes\AssetHyperlinkValidationInterface;
use Contentful\Management\Resource\ContentType\Validation\Nodes\EmbeddedAssetBlockValidationInterface;
use Contentful\Management\Resource\ContentType\Validation\Nodes\EmbeddedEntryBlockValidationInterface;
use Contentful\Management\Resource\ContentType\Validation\Nodes\EmbeddedEntryInlineValidationInterface;
use Contentful\Management\Resource\ContentType\Validation\Nodes\EntryHyperlinkValidationInterface;

class NodesValidation implements ValidationInterface
{
    private const VALID_FIELD_TYPES = ['RichText'];

    /**
     * @var array
     */
    private $assetHyperlinkValidations;

    /**
     * @var array
     */
    private $embeddedAssetBlockValidations;

    /**
     * @var array
     */
    private $embeddedEntryBlockValidations;

    /**
     * @var array
     */
    private $embeddedEntryInlineValidations;

    /**
     * @var array
     */
    private $entryHyperlinkValidations;

    public function __construct(
        array $assetHyperlinkValidations = [],
        array $embeddedAssetBlockValidations = [],
        array $embeddedEntryBlockValidations = [],
        array $embeddedEntryInlineValidations = [],
        array $entryHyperlinkValidations = []
    ) {
        $this->assetHyperlinkValidations = $assetHyperlinkValidations;
        $this->embeddedAssetBlockValidations = $embeddedAssetBlockValidations;
        $this->embeddedEntryBlockValidations = $embeddedEntryBlockValidations;
        $this->embeddedEntryInlineValidations = $embeddedEntryInlineValidations;
        $this->entryHyperlinkValidations = $entryHyperlinkValidations;
    }

    /**
     * {@inheritdoc}
     */
    public static function getValidFieldTypes(): array
    {
        return self::VALID_FIELD_TYPES;
    }

    public function getAssetHyperlinkValidations(): array
    {
        return $this->assetHyperlinkValidations;
    }

    public function addAssetHyperlinkValidation(AssetHyperlinkValidationInterface $assetHyperlinkValidation): self
    {
        $this->assetHyperlinkValidations[] = $assetHyperlinkValidation;

        return $this;
    }

    public function resetAssetHyperlinkValidations(): self
    {
        $this->assetHyperlinkValidations = [];

        return $this;
    }

    public function getEmbeddedAssetBlockValidations(): array
    {
        return $this->embeddedAssetBlockValidations;
    }

    public function addEmbeddedAssetBlockValidation(
        EmbeddedAssetBlockValidationInterface $embeddedAssetBlockValidation
    ): self {
        $this->embeddedAssetBlockValidations[] = $embeddedAssetBlockValidation;

        return $this;
    }

    public function resetEmbeddedAssetBlockValidations(): self
    {
        $this->embeddedAssetBlockValidations = [];

        return $this;
    }

    public function getEmbeddedEntryBlockValidations(): array
    {
        return $this->embeddedEntryBlockValidations;
    }

    public function addEmbeddedEntryBlockValidation(
        EmbeddedEntryBlockValidationInterface $embeddedEntryBlockValidation
    ): self {
        $this->embeddedEntryBlockValidations[] = $embeddedEntryBlockValidation;

        return $this;
    }

    public function resetEmbeddedEntryBlockValidations(): self
    {
        $this->embeddedEntryBlockValidations = [];

        return $this;
    }

    public function getEmbeddedEntryInlineValidations(): array
    {
        return $this->embeddedEntryInlineValidations;
    }

    public function addEmbeddedEntryInlineValidation(
        EmbeddedEntryInlineValidationInterface $embeddedEntryInlineValidation
    ): self {
        $this->embeddedEntryInlineValidations[] = $embeddedEntryInlineValidation;

        return $this;
    }

    public function resetEmbeddedEntryInlineValidations(): self
    {
        $this->embeddedEntryInlineValidations = [];

        return $this;
    }

    public function getEntryHyperlinkValidations(): array
    {
        return $this->entryHyperlinkValidations;
    }

    public function addEntryHyperlinkValidation(EntryHyperlinkValidationInterface $entryHyperlinkValidation): self
    {
        $this->entryHyperlinkValidations[] = $entryHyperlinkValidation;

        return $this;
    }

    public function resetEntryHyperlinkValidations(): self
    {
        $this->entryHyperlinkValidations = [];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return [
            'nodes' => \array_filter(
                [
                    'asset-hyperlink' => $this->assetHyperlinkValidations,
                    'embedded-asset-block' => $this->embeddedAssetBlockValidations,
                    'embedded-entry-block' => $this->embeddedEntryBlockValidations,
                    'embedded-entry-inline' => $this->embeddedEntryInlineValidations,
                    'entry-hyperlink' => $this->entryHyperlinkValidations,
                ]
            ),
        ];
    }
}
