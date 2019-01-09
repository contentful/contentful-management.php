<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\ContentType\Field;

use Contentful\Management\Resource\ContentType\Validation\ValidationInterface;

/**
 * ArrayField class.
 */
class ArrayField extends BaseField
{
    /**
     * @var string[]
     */
    const VALID_ITEM_TYPES = ['Symbol', 'Link'];

    /**
     * @var string[]
     */
    const VALID_LINK_TYPES = ['Asset', 'Entry'];

    /**
     * Type for items.
     *
     * Valid values are:
     * - Symbol
     * - Link
     *
     * @var string
     */
    private $itemsType;

    /**
     * (Array of links only) Type of links.
     *
     * Valid values are:
     * - Asset
     * - Entry
     *
     * @var string|null
     */
    private $itemsLinkType;

    /**
     * @var ValidationInterface[]
     */
    private $itemsValidations = [];

    /**
     * ArrayField constructor.
     *
     * @param string      $id
     * @param string      $name
     * @param string      $itemsType
     * @param string|null $itemsLinkType
     */
    public function __construct(string $id, string $name, string $itemsType, string $itemsLinkType = \null)
    {
        parent::__construct($id, $name);

        $this->setItemsType($itemsType);
        $this->setItemsLinkType($itemsLinkType);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'Array';
    }

    /**
     * @return string
     */
    public function getItemsType(): string
    {
        return $this->itemsType;
    }

    /**
     * @param string $itemsType
     *
     * @throws \InvalidArgumentException
     *
     * @return static
     */
    public function setItemsType(string $itemsType)
    {
        if (!$this->isValidItemType($itemsType)) {
            throw new \InvalidArgumentException(\sprintf(
                'Invalid items type "%s". Valid values are %s.',
                $itemsType,
                \implode(', ', self::VALID_ITEM_TYPES)
            ));
        }

        $this->itemsType = $itemsType;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getItemsLinkType()
    {
        return $this->itemsLinkType;
    }

    /**
     * @param string|null $itemsLinkType
     *
     * @throws \InvalidArgumentException
     *
     * @return static
     */
    public function setItemsLinkType(string $itemsLinkType = \null)
    {
        if ($itemsLinkType && 'Link' === $this->itemsType && !$this->isValidLinkType($itemsLinkType)) {
            throw new \InvalidArgumentException(\sprintf(
                'Invalid items link type "%s". Valid values are %s.',
                $itemsLinkType,
                \implode(', ', self::VALID_LINK_TYPES)
            ));
        }

        $this->itemsLinkType = $itemsLinkType;

        return $this;
    }

    /**
     * @return ValidationInterface[]
     */
    public function getItemsValidations(): array
    {
        return $this->itemsValidations;
    }

    /**
     * @param ValidationInterface[] $itemsValidations
     *
     * @return static
     */
    public function setItemsValidations(array $itemsValidations)
    {
        $this->itemsValidations = [];

        \array_map([$this, 'addItemsValidation'], $itemsValidations);

        return $this;
    }

    /**
     * @param ValidationInterface $validation
     *
     * @throws \InvalidArgumentException
     *
     * @return static
     */
    public function addItemsValidation(ValidationInterface $validation)
    {
        if (!\in_array($this->itemsType, $validation::getValidFieldTypes(), \true)) {
            throw new \InvalidArgumentException(\sprintf(
                'The validation "%s" can not be used for fields of type "%s".',
                \get_class($validation),
                $this->itemsType
            ));
        }

        $this->itemsValidations[] = $validation;

        return $this;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    private function isValidItemType(string $type): bool
    {
        return \in_array($type, self::VALID_ITEM_TYPES, \true);
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    private function isValidLinkType(string $type): bool
    {
        return \in_array($type, self::VALID_LINK_TYPES, \true);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();

        $items = ['type' => $this->itemsType];
        if ('Link' === $this->itemsType) {
            $items['linkType'] = $this->itemsLinkType;
        }
        if ($this->itemsValidations) {
            $items['validations'] = $this->itemsValidations;
        }

        $data['items'] = $items;

        return $data;
    }
}
