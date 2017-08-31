<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

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
    public function __construct(string $id, string $name, string $itemsType, string $itemsLinkType = null)
    {
        parent::__construct($id, $name);

        if (!self::isValidItemType($itemsType)) {
            throw new \RuntimeException(sprintf(
                'Invalid items type "%s". Valid values are %s.',
                $itemsType,
                implode(', ', self::VALID_ITEM_TYPES)
            ));
        }
        if ($itemsType === 'Link' && !self::isValidLinkType($itemsLinkType)) {
            throw new \RuntimeException(sprintf(
                'Invalid items link type "%s". Valid values are %s.',
                $itemsLinkType,
                implode(', ', self::VALID_LINK_TYPES)
            ));
        }

        $this->itemsType = $itemsType;
        $this->itemsLinkType = $itemsLinkType;
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
     * @return static
     */
    public function setItemsType(string $itemsType)
    {
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
     * @return static
     */
    public function setItemsLinkType(string $itemsLinkType = null)
    {
        $this->itemsLinkType = $itemsLinkType;

        return $this;
    }

    /**
     * @return ValidationInterface[]
     */
    public function getItemsValidations(): array
    {
        return $this->itemsValidations !== null ? $this->itemsValidations : [];
    }

    /**
     * @param ValidationInterface[] $itemsValidations
     *
     * @return static
     */
    public function setItemsValidations(array $itemsValidations)
    {
        foreach ($itemsValidations as $validation) {
            if (!in_array($this->getItemsType(), $validation::getValidFieldTypes())) {
                throw new \RuntimeException(sprintf(
                    'The validation "%s" can not be used for fields of type "%s".',
                    get_class($validation),
                    $this->getType()
                ));
            }
        }

        $this->itemsValidations = $itemsValidations;

        return $this;
    }

    /**
     * @param ValidationInterface $validation
     *
     * @return static
     */
    public function addItemsValidation(ValidationInterface $validation)
    {
        if (!in_array($this->getItemsType(), $validation::getValidFieldTypes())) {
            throw new \RuntimeException(sprintf(
                'The validation "%s" can not be used for fields of type "%s".',
                get_class($validation),
                $this->getType()
            ));
        }

        $this->validations[] = $validation;

        return $this;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    private static function isValidItemType(string $type): bool
    {
        return in_array($type, self::VALID_ITEM_TYPES);
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    private static function isValidLinkType(string $type): bool
    {
        return in_array($type, self::VALID_LINK_TYPES);
    }

    /**
     * Returns an array to be used by "json_encode" to serialize objects of this class.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();

        $items = ['type' => $this->itemsType];
        if ($this->itemsType === 'Link') {
            $items['linkType'] = $this->itemsLinkType;
        }

        $data['items'] = $items;

        return $data;
    }
}
